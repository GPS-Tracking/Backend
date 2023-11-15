<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    $app->get('/data/getAll', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT * FROM `map-tracking`");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            // Manually set the response as JSON
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/data/device', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT Name, Latitude, Longitude FROM `map-tracking`");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            // Manually set the response as JSON
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/aman', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_Aman FROM `map-tracking` WHERE Status = 'Aman'");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            // Manually set the response as JSON
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/warning', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_Warning FROM `map-tracking` WHERE Status = 'Warning'");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            // Manually set the response as JSON
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });

    $app->get('/status/SOS', function (Request $request, Response $response) {
        try {
            $db = $this->get(PDO::class);
            $sth = $db->prepare("SELECT COUNT(*) AS Jumlah_Device_SOS FROM `map-tracking` WHERE Status = 'SOS'");
            $sth->execute();
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $data = array(
                "status" => "PDOException",
                "message" => $e->getMessage()
            );
            // Manually set the response as JSON
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    });
};
