<?php

namespace App\Exceptions;

/*use App\Repositories\SaasExceptionLogRepository;*/
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
        //var_dump($exception->getFile().$exception->getLine());

       /* $data = [
            'title' => $exception->getMessage(),
            'file'  => $exception->getFile(),
            'line'  => $exception->getLine(),
            'log_file'  => 'laravel-'.date('Y-m-d').'.log',
            'created_at' => time()
        ];

        if (!empty($data['title'])) {
            app(SaasExceptionLogRepository::class)->insert($data);
        }*/

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
