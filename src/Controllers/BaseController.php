<?php

namespace BastienJcln\SwissCooking\Controllers;

use Slim\Views\PhpRenderer;

abstract class BaseController
{
    /**
     * @var PhpRenderer
     */
    protected PhpRenderer $view;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->view = new PhpRenderer(__DIR__ . '/../../views', [
            'title' => 'The Wallet',
            'withMenu' => true,
        ]);

        $this->view->setLayout("layout.php");
    }
}
