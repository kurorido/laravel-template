<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class StarterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $CamelCase = $this->argument('name');
        $SnakeCase = Str::snake($CamelCase);
        $PluralName = Str::plural($SnakeCase);

        // Create Migration & Model
        Artisan::call("make:model -m $CamelCase");

        // Create CRUD Controller
        $string = file_get_contents(resource_path('stubs/StubController.php.stub'));
        // Replace {CamelCase}
        $string = str_replace("{CamelCase}", $CamelCase, $string);
        // Replace {SnakeCase}
        $string = str_replace("{SnakeCase}", $SnakeCase, $string);
        // Replace {PluralName}
        $string = str_replace("{PluralName}", $PluralName, $string);

        file_put_contents(app_path("Http/Controllers/{$CamelCase}Controller.php"), $string);

        // Create Routes
        $string = file_get_contents(resource_path('stubs/StubRoutes.php.stub'));
        // Replace {CamelCase}
        $string = str_replace("{CamelCase}", $CamelCase, $string);
        // Replace {SnakeCase}
        $string = str_replace("{SnakeCase}", $SnakeCase, $string);
        // Replace {PluralName}
        $string = str_replace("{PluralName}", $PluralName, $string);

        $routesFile = base_path('routes/api.php');
        file_put_contents($routesFile, $string, FILE_APPEND | FILE_USE_INCLUDE_PATH);

        $routes = file_get_contents($routesFile);

        if (preg_match('/use [A-Za-z\\\\]+;/', $routes, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1];

            // Prepend a line before the line
            $import = implode("\\", ['App', 'Http', 'Controllers', "{$CamelCase}Controller;\n"]);
            $newRoutes = substr_replace($routes, "use $import", $pos, 0);

            // Write the updated content back to the file
            file_put_contents($routesFile, $newRoutes);
        }

        // Create Policy
        $string = file_get_contents(resource_path('stubs/StubPolicy.php.stub'));
        // Replace {CamelCase}
        $string = str_replace("{CamelCase}", $CamelCase, $string);
        // Replace {SnakeCase}
        $string = str_replace("{SnakeCase}", $SnakeCase, $string);
        // Replace {PluralName}
        $string = str_replace("{PluralName}", $PluralName, $string);

        if (!file_exists(app_path("Polices"))) {
            mkdir(app_path("Polices"));
        }

        file_put_contents(app_path("Polices/{$CamelCase}Policy.php"), $string);
    }
}
