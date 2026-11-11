<?php

namespace App\Console\Commands;

use App\Http\Middleware\Authenticate;
use Illuminate\Console\Command;
use Illuminate\Auth\Middleware\Authenticate as LaravelAuthenticate;
use Illuminate\Support\Facades\Route;

class GenerateApiDocumentation extends Command
{
    protected $signature = 'api-docs:generate';

    protected $description = 'Generate an OpenAPI document from registered API routes';

    public function handle(): int
    {
        $paths = [];

        foreach (Route::getRoutes() as $route) {
            $uri = '/'.ltrim($route->uri(), '/');

            if (($uri !== '/api' && !str_starts_with($uri, '/api/'))
                || in_array($uri, ['/api/documentation', '/api/oauth2-callback'], true)) {
                continue;
            }

            $openApiPath = preg_replace('/\{([^}?]+)\?\}/', '{$1}', $uri);
            $middleware = app('router')->gatherRouteMiddleware($route);
            $authMiddleware = array_values(array_filter($middleware, static function (string $item): bool {
                $class = explode(':', $item, 2)[0];

                return in_array($class, [Authenticate::class, LaravelAuthenticate::class], true);
            }));

            foreach ($route->methods() as $method) {
                $method = strtolower($method);
                if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete'], true)) {
                    continue;
                }

                $operation = [
                    'tags' => [$this->tagFor($openApiPath)],
                    'summary' => $route->getActionName(),
                    'operationId' => $this->operationId($method, $openApiPath),
                    'responses' => [
                        'default' => [
                            'description' => 'Response defined by the API endpoint.',
                        ],
                    ],
                ];

                preg_match_all('/\{([^}]+)\}/', $openApiPath, $matches);
                if ($matches[1] !== []) {
                    $operation['parameters'] = array_map(
                        static fn (string $name): array => [
                            'name' => $name,
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'string'],
                        ],
                        $matches[1]
                    );
                }

                if ($authMiddleware !== []) {
                    $operation['security'] = [['bearerAuth' => []]];
                    $operation['responses']['401'] = [
                        'description' => 'Authentication is required.',
                    ];
                }

                $paths[$openApiPath][$method] = $operation;
            }
        }

        ksort($paths);

        $document = [
            'openapi' => '3.0.3',
            'info' => [
                'title' => config('app.name').' API',
                'version' => '1.0.0',
                'description' => 'Generated from the registered Laravel API routes.',
            ],
            'servers' => [['url' => '/']],
            'paths' => (object) $paths,
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'Sanctum token',
                    ],
                ],
            ],
        ];

        $directory = config('l5-swagger.defaults.paths.docs', storage_path('api-docs'));
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            $this->error("Unable to create API documentation directory: {$directory}");

            return self::FAILURE;
        }

        $file = $directory.DIRECTORY_SEPARATOR.config('l5-swagger.documentations.default.paths.docs_json', 'api-docs.json');
        $json = json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false || file_put_contents($file, $json.PHP_EOL) === false) {
            $this->error("Unable to write API documentation file: {$file}");

            return self::FAILURE;
        }

        $operationCount = array_sum(array_map('count', $paths));
        $this->info("Generated {$operationCount} API operations in {$file}");

        return self::SUCCESS;
    }

    private function tagFor(string $path): string
    {
        $segments = explode('/', trim($path, '/'));
        $tag = $segments[2] ?? 'api';

        return ucfirst($tag);
    }

    private function operationId(string $method, string $path): string
    {
        return trim(preg_replace('/[^A-Za-z0-9]+/', '_', $method.'_'.$path), '_');
    }
}
