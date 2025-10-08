<?php
namespace Presentation\Controllers;

use Application\StatsQuery;
use Presentation\MVC\ActionResult;
use Presentation\MVC\Controller;

class Home extends Controller {
    public function __construct(
        private StatsQuery $stats
    ) {
    }

    public function GET_Index(): ActionResult {
        return $this->view('home', [
            'stats' => $this->stats->execute(),
            'loginController' => 'User',
            'registerController' => 'User',
        ]);
    }
}