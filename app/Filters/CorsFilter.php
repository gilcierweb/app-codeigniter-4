<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CorsFilter implements FilterInterface
{
public function before(RequestInterface $request, $arguments = null)
{
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');

if ($request->getMethod() === 'options') {
exit(0);
}
}

public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
{
// Não é necessário fazer nada aqui
}
}