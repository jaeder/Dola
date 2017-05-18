<?php

namespace DFZ\Dola\Widgets;

use Arrilot\Widgets\AbstractWidget;
use DFZ\Dola\Facades\Dola;

class PageDimmer extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Dola::model('Page')->count();
        $string = $count == 1 ? 'page' : 'pages';

        return view('dola::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-group',
            'title'  => "{$count} {$string}",
            'text'   => "You have {$count} {$string} in your database. Click on button below to view all pages.",
            'button' => [
                'text' => 'View all pages',
                'link' => route('dola.pages.index'),
            ],
            'image' => dola_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}
