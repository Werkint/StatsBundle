<?php
namespace Werkint\Bundle\StatsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Werkint\Bundle\FrameworkExtraBundle\Annotation as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * AjaxController.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class AjaxController extends Controller
{
    // -- Services ---------------------------------------

    /**
     * @return \Werkint\Bundle\StatsBundle\Service\StatsDirector
     */
    protected function serviceStats()
    {
        return $this->get('werkint_stats.statsdirector');
    }

    // -- Actions ---------------------------------------

    public function statAction(Request $req, $class)
    {
        $count = $this->serviceStats()->getStat(
            str_replace('_', '.', $class),
            (array)$req->request->get('params'),
            true
        );
        return new Response(json_encode([
            'count' => $count,
        ]));
    }
}
