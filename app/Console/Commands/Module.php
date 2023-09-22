<?php

namespace App\Console\Commands;

use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\User\src\Models\User;
use Modules\User\src\Repositories\UserRepositoryInterface;
use Illuminate\Support\Str;

class Module extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create module CLI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        if (File::exists(base_path('modules/' . $name))) {
            $this->error("Module already exists!");
        } else {
            File::makeDirectory(base_path("/modules/{$name}"), 0755, true, true);

            //Configs
            $configFolder = base_path("/modules/{$name}/configs");
            $this->makeDirectory($configFolder);

            //Helper
            $helperFolder = base_path("/modules/{$name}/helper");
            $this->makeDirectory($helperFolder);

            //Migrations
            $migrationFolder = base_path("/modules/{$name}/migrations");
            $this->makeDirectory($migrationFolder);

            //Resources
            $resourceFolder = base_path("/modules/{$name}/resources");
            if(!File::exists($resourceFolder)){
                $this->makeDirectory($resourceFolder);

                //Language
                $langFolder = base_path("/modules/{$name}/resources/lang");
                $this->makeDirectory($langFolder);

                //View
                $viewFolder = base_path("/modules/{$name}/resources/views");
                $this->makeDirectory($viewFolder);
            }

            //Routes
            $routesFolder = base_path("/modules/{$name}/routes");
            if(!File::exists($routesFolder)){

                $this->makeDirectory($routesFolder);

                $routeFile = base_path("/modules/{$name}/routes/routes.php");

                $content = "<?php \n use Illuminate\Support\Facades\Route;";

                $this->putFile($routeFile,$content);
            }

            //src

            $srcFolder = base_path("/modules/{$name}/src");
            if(!File::exists($srcFolder)){
                $this->makeDirectory($srcFolder);

                //Command
                $commandFolder = base_path("/modules/{$name}/src/Commands");
                $this->makeDirectory($commandFolder);

                //Http
                $httpFolder = base_path("/modules/{$name}/src/Http");
                if(!File::exists($httpFolder)){
                    $this->makeDirectory($httpFolder);

                    //Controller
                    $controllerFolder = base_path("/modules/{$name}/src/Http/Controllers");
                    $this->makeDirectory($controllerFolder);

                    //Middlewares
                    $middlewareFolder = base_path("/modules/{$name}/src/Http/Middlewares");
                    $this->makeDirectory($middlewareFolder);

                    //Requests
                    $requestFolder = base_path("/modules/{$name}/src/Http/Requests");
                    $this->makeDirectory($requestFolder);

                }

                //Model
                $modelFolder = base_path("/modules/{$name}/src/Models");
                $this->makeDirectory($modelFolder);

                //Repository
                $this->makeRepository($name);
            }


            $this->info("Module created successfully!");
        }
    }

    public function makeDirectory($folderName)
    {
        if(!File::exists($folderName)){
            File::makeDirectory($folderName,0755,true, true);
        }
    }

    public function putFile($fileName,$content)
    {
        if(!File::exists($fileName)){
            File::put($fileName,$content);
        }
    }

    public function makeRepository($module)
    {
        $RepositoryFolder = base_path("/modules/{$module}/src/Repositories");
        if(!File::exists($RepositoryFolder)){
            $this->makeDirectory($RepositoryFolder);

            //Repository File
            $repositoryFile =  base_path("/modules/{$module}/src/Repositories/{$module}Repository.php");
            $repositoryFileContent =
                file_get_contents(app_path('Console/Commands/Templates/ModuleRepository.txt'));
            $repositoryFileContent = Str::replace('{module}',$module,$repositoryFileContent);
            $this->putFile($repositoryFile,$repositoryFileContent);

            //RepositoryInterface File
            $repositoryInterfaceFile=  base_path("/modules/{$module}/src/Repositories/{$module}RepositoryInterface.php");
            $repositoryInterfaceFileContent =
                file_get_contents(app_path('Console/Commands/Templates/ModuleRepositoryInterface.txt'));
            $repositoryInterfaceFileContent = Str::replace('{module}', $module, $repositoryInterfaceFileContent);
            $this->putFile($repositoryInterfaceFile,$repositoryInterfaceFileContent);

        }
    }
}
