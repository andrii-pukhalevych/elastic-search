<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.0.1
 * @license       https://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\ElasticSearch;

use Cake\Collection\Collection;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Datasource\FactoryLocator;
use Cake\ElasticSearch\View\Form\DocumentContext;
use Cake\Event\EventManager;
use Traversable;

/**
 * Elasticsearch plugin
 */
class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        $indexRegistry = new IndexRegistry();
        FactoryLocator::add('Elastic', $indexRegistry);
        FactoryLocator::add('ElasticSearch', $indexRegistry);

        // Attach the document context into FormHelper.
        EventManager::instance()->on('View.beforeRender', function ($event): void {
            $view = $event->getSubject();
            $view->Form->addContextProvider('elastic', function ($request, $data) {
                $first = null;
                if (is_array($data['entity']) || $data['entity'] instanceof Traversable) {
                    $first = (new Collection($data['entity']))->first();
                }
                if ($data['entity'] instanceof Document || $first instanceof Document) {
                    return new DocumentContext($request, $data);
                }
            });
        });
    }
}
