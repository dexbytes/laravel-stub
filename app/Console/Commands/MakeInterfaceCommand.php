<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;
use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;


class MakeInterfaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name} {--md|module=default} {--all} {--m|model} {--c|controller} {--rs|resource} {--rq|request} {--s|swagger}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make an Interface Class';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * the stub variables present in stub to its value
     *
     * @var string
     */
    protected $module = 'default';

     /**
     * the stub variables present in stub to its value
     *
     * @var string
     */
    protected $replace = [];

    

     /**
     * the stub variables name in stub to its value
     *
     * @var string
     */
    protected $name = '';    
 
    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
                 
        if ($this->option('module')) {
            $this->module = $this->parseModule($this->option('module'));
            $this->info("Module Name: ".   $this->module);
        }        

        $this->name = $this->parseModel($this->argument('name'));
        $this->info("Name: ".   $this->name);

        $this->buildClass();
        
    }


     /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass()
    {

        if ($this->option('model')) {
            $this->buildModelReplacements();           
        }

        if ($this->option('request')) {
            $this->buildFormRequestReplacements();
        }        

        if ($this->option('resource')) {
            $this->buildFormResourceReplacements();
        }  

        if ($this->option('controller')) {
            $this->buildFormControllerReplacements();
        }          
      
        $this->info('Replace - '. json_encode($this->replace));
    }


   /**
     * Build the model replacement values.
     *
     * @return array
     */
    protected function buildModelReplacements()
    {       
        $modelClass = $this->qualifyModel($this->name);    
        
        $this->replace =  array_merge( $this->replace , [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
            '{{class}}' => class_basename($modelClass),
            '{{ class }}' => class_basename($modelClass),
             'path' => Str::beforeLast($modelClass, '\\'),
            '{{ namespace }}' => Str::beforeLast($modelClass, '\\'),
            '{{namespace}}' =>  Str::beforeLast($modelClass, '\\')
        ]);

        $this->makeFile(class_basename($modelClass), 'model');
             
    }


       /**
     * Build the model replacement values.
     *
     * @return array
     */
    protected function buildFormControllerReplacements()
    {       
        $modelClass = $this->qualifyModel($this->name);    
        $rootNamespace = $this->rootNamespace();

        if($this->module != 'default'){
            $namespace = 'App\\Http\\Controllers\\Api\\'.$this->module;
        }else{
            $namespace = 'App\\Http\\Controllers\\Api';
        }

        $className =  class_basename($modelClass).'Controller';
        
        $this->replace =  array_merge( $this->replace , [
            '{{ rootNamespace }}' => $rootNamespace,
            '{{rootNamespace}}' => $rootNamespace,
            '{{class}}' => $className,
            '{{ class }}' => $className,          
            'path' => $namespace,
            '{{ namespace }}' => $namespace,
            '{{namespace}}' => $namespace,
       ]);      

       $this->makeFile($className, 'controller');
             
    }

   /**
     * Build the model replacement values.
     * @return array
     */
    protected function buildFormRequestReplacements()
    {
        $modelClass =$this->qualifyModel($this->name);

        [$namespace, $storeRequestClass, $updateRequestClass] = [
            'Illuminate\\Http', 'Request', 'Request',
        ];

        if ($this->option('request')) {
            if($this->module != 'default'){
                $namespace = 'App\\Http\\Requests\\'.$this->module;
            }else{
                $namespace = 'App\\Http\\Requests';
            }
            
            $this->replace['{{ namespace }}'] = $namespace;
            $this->replace['path'] = $namespace;

            [$storeRequestClass, $updateRequestClass] = $this->generateFormRequests(
                $modelClass, $storeRequestClass, $updateRequestClass
            );
        }

        $namespacedRequests = $namespace.'\\'.$storeRequestClass.';';

        if ($storeRequestClass !== $updateRequestClass) {
            $namespacedRequests .= PHP_EOL.'use '.$namespace.'\\'.$updateRequestClass.';';
        }

        $this->replace = array_merge($this->replace, [
            '{{ storeRequest }}' => $storeRequestClass,
            '{{storeRequest}}' => $storeRequestClass,
            '{{ updateRequest }}' => $updateRequestClass,
            '{{updateRequest}}' => $updateRequestClass,
            '{{ namespacedStoreRequest }}' => $namespace.'\\'.$storeRequestClass,
            '{{namespacedStoreRequest}}' => $namespace.'\\'.$storeRequestClass,
            '{{ namespacedUpdateRequest }}' => $namespace.'\\'.$updateRequestClass,
            '{{namespacedUpdateRequest}}' => $namespace.'\\'.$updateRequestClass,
            '{{ namespacedRequests }}' => $namespacedRequests,
            '{{namespacedRequests}}' => $namespacedRequests,
        ]);
    }


    
    /**
     * Generate the form requests for the given model and classes.
     *
     * @param  string  $modelClass
     * @param  string  $storeRequestClass
     * @param  string  $updateRequestClass
     * @return array
     */
    protected function generateFormRequests($modelClass, $storeRequestClass, $updateRequestClass)
    {
        $storeRequestClass = 'Store'.class_basename($modelClass).'Request'; 
        $this->replace['{{ class }}'] = $storeRequestClass;       
        $this->makeFile($storeRequestClass, 'request');

        $updateRequestClass = 'Update'.class_basename($modelClass).'Request';
        $this->replace['{{ class }}'] = $updateRequestClass;   
        $this->makeFile($updateRequestClass, 'request');

        return [$storeRequestClass, $updateRequestClass];
    }
    

     /**
     * Build the model replacement values.
     * @return array
     */
    protected function buildFormResourceReplacements()
    {
        $modelClass =$this->qualifyModel($this->name);

        [$namespace, $resourceClass, $collectionClass] = [
            'Illuminate\\Http', '\\Resources\\Json\\JsonResource', '\\Resources\\Json\\JsonResource',
        ];

        if ($this->option('resource')) {
            if($this->module != 'default'){
                $namespace = 'App\\Http\\Resource\\'.$this->module;
            }else{
                $namespace = 'App\\Http\\Resource';
            }
            
            $this->replace['{{ namespace }}'] = $namespace;
            $this->replace['path'] = $namespace;

            [$resourceClass, $collectionClass] = $this->generateFormResourceCollection(
                $modelClass, $resourceClass, $collectionClass
            );
        }

        $namespacedResources = $namespace.'\\'.$resourceClass.';';

        if ($resourceClass !== $collectionClass) {
            $namespacedResources .= PHP_EOL.'use '.$namespace.'\\'.$collectionClass.';';
        }

        $this->replace = array_merge($this->replace, [
            '{{ resourceClass }}' => $resourceClass,
            '{{resourceClass}}' => $resourceClass,
            '{{ collectionClass }}' => $collectionClass,
            '{{collectionClass}}' => $collectionClass,
            '{{ namespacedResource }}' => $namespace.'\\'.$resourceClass,
            '{{namespacedResource}}' => $namespace.'\\'.$resourceClass,
            '{{ namespacedCollection }}' => $namespace.'\\'.$collectionClass,
            '{{namespacedCollection}}' => $namespace.'\\'.$collectionClass,
            '{{ namespacedResources }}' => $namespacedResources,
            '{{namespacedResources}}' => $namespacedResources,
        ]);
    }
    
    /**
     * Generate the form resource for the given model and classes.
     *
     * @param  string  $modelClass
     * @param  string  $resourceClass
     * @param  string  $collectionClass
     * @return array
     */
    protected function generateFormResourceCollection($modelClass, $resourceClass, $collectionClass)
    {
        $resourceClass = class_basename($modelClass).'Resource'; 
        $this->replace['{{ class }}'] = $resourceClass;       
        $this->makeFile($resourceClass, 'resource');

        $collectionClass = class_basename($modelClass).'Collection';
        $this->replace['{{ class }}'] = $collectionClass;   
        $this->makeFile($collectionClass, 'resource');

        return [$resourceClass, $collectionClass];
    }
 
     /**
     * Replace the variables(key) with the desire value
     *
     * @param $name, $type (file type)
     * @return bool|mixed|string
     */
    public function makeFile($name, $type)
    {
        $path = base_path($this->replace['path']) .'\\'.$name.'.php';
        $this->makeDirectory(dirname($path));
        $contents = $this->getSourceFile($type);
        
        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);           
            $this->info("File : {$path} created");            
        } else {
            $this->info("File : {$path} already exits");
        }
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStubPath($type)
    {  
     
        $stubsPath = '';
        switch ($type) {
            case "request":
                $stubsPath = __DIR__ . '/../../../stubs/dex/request.stub';
                break;
            case "resource":
                $stubsPath =  __DIR__ . '/../../../stubs/dex/resource.stub';
                break; 
            case "model":
                $stubsPath =  __DIR__ . '/../../../stubs/dex/model.stub';
                break; 
            case "controller":
                $this->info("->". base_path('App\Http\Controller\Api\BaseController.php'));
                if ($this->files->exists(base_path('App\\Http\\Controllers\\Api\\BaseController.php'))) {
                    if ($this->option('swagger')) {
                        $stubsPath =  __DIR__ . '/../../../stubs/dex/controller.base.api.swagger.stub';
                    }else{
                        $stubsPath =  __DIR__ . '/../../../stubs/dex/controller.base.api.stub';
                    }
                }else{
                    $stubsPath =  __DIR__ . '/../../../stubs/dex/controller.api.stub';
                }
                break;          
          default:
             $this->info("Stubs file not exist");  
        }

        return $stubsPath;   

    }
    
    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->getSingularClassName($model);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModule($module)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $module)) {
            throw new InvalidArgumentException('Module name contains invalid characters.');
        }

        return $this->getSingularClassName($module);

    }



     /**
     * Qualify the given model class base name.
     *
     * @param  string  $model
     * @return string
     */
    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        if($this->module != 'default'){
            $model = $this->module.'\\'.$model;
        }

        return is_dir(app_path('Models'))
                    ? $rootNamespace.'Models\\'.$model
                    : $rootNamespace.$model;
    }

     /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
    **
    * Map the stub variables present in stub to its value
    *
    * @return array
    *
    */
    public function getStubVariables()
    {
        return  $this->replace;
    }

    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile($type)
    {
        return $this->getStubContents($this->getStubPath($type), $this->getStubVariables());
    }


    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace($search , $replace, $contents);
        }

        return $contents;

    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        return base_path('App\\Interfaces') .'\\' .$this->getSingularClassName($this->argument('name')) . 'Interface.php';
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

}