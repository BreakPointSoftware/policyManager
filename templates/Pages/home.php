<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.1/normalize.css">

    <?= $this->Html->css('milligram.min.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('home.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <div class="container text-center">
            <a href="https://cakephp.org/" target="_blank">
                <img alt="CakePHP" src="https://cakephp.org/v2/img/logos/CakePHP_Logo.svg" width="350" />
            </a>
            <h1>
                Welcome to the Policy Manager
            </h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <div class="content">
            
            <h1>Policy Manager</h1> 
            <h2>Ocasta Programming Test/Example</h2>
            <p>What follows is a small development example using Cake 4.0 framework.   The objective is to make a simple Policy Manager.</p>
            <Br>
            <p>The concept is to resolve the business problem where you have a number of policies that share common elements.  For example, you could write a policy that refers to the Brighton studio address, “9 Dyke Road, Brighton, BN1 3FE, United Kingdom”.  Rather than write this by hand into every policy that which requires it, you have a simple #Tag aliasing system.  When the policy author wants to refer to the address, they just need to write #OFFICEADDRESS.   Then when the policy is viewed or printed it would display the correct address.</p>
            <Br>
            <p>The concept has a potentially sprawling scope, the main focus of this exercise is to prove the concept and learn CakePHP.</p>
            <Br>
            <h2>Getting Started</h2>
            <p>You can download the full zip of the project source <a href="http://asklittlesister.com/policyManager/policyManager.zip">here</a>.  Further down the page, you can find the SQL create statements for building the database tables if you wish to host it locally.   Alternately, the zipped version will connect to the hosted MySQL database for simplicity. </p>
            <Br>
            <p>To start you can log in at:<a href="http://asklittlesister.com/policyManager/users/login">Login</a></p>
            
            <Br>
            <p>I have set up the following test accounts:</p>
            <Br>
            <div class="row">
                <div class="column">
                <div class="message default">
                <small>
                Greg User:GregWilliamBryant@gmail.com Password:test</br>
                Martin User:martin@ocasta.recruitee.com	Password:test</br>
                Jason Fry User:jason@ocasta.recruitee.com Password:test</br>
                </small>
                    </div>
                </div>
            </div>
            
            <p>You can also make new accounts by going to:<a href="http://asklittlesister.com/policyManager/users/register">Register</a></p>
            <Br>
            <p>Once Logged in, the first thing you want to do is view some policies, click the <b>Policies</b> link at the top of the screen.</p><Br>

            <p>There you will see a couple of policies, ‘Late Night Food Policy’ and ‘Fire policy #FirepolicyNum’.</p><Br>

            <p>If you click View, you can see the rendered version for the policy, which has the Tags converted. Conceptually these tags have been referred to in the source as policyComponents.</p><Br>

            <p>From the policy, you can click <b>Edit Components</b>, this will show you a list of all the components used by the policy; if you wish you can make changes to them here.</p><Br>

            <p><b>Edit Policy</b> will allow you to edit the full body text, here you can add or use any of your previously generated policyComponents by typing or generating new ones using #myNewcomponent syntax.</p><Br>

            <p>These components will then be auto-generated when you save the policy.   You can then use them immediately by clicking on <b>Edit Components</b> again.</p><Br>

            <p>The auto-generated components will have placeholder titles and descriptions. You can edit these by going to the <b>Components</b> tab at the top of the screen, and then editing the relevant component.</p><Br>

            <p>Finally, you can approve policies in the policy list view,  approved policies will be stamped with today's date.</p><Br>

            <h2>Task Scope discussion:</h2>
            <p>The current implementation of the policy manager is understandably refined, the main focus has been on learning CakePHP’s framework and demonstrating it uses through a few core elements.
            CakePHP is a convention over configuration framework, you can generate boilerplate interfaces, and then view them and easily view the cake bake command line tools. Although this is very powerful, the first task was to set about breaking the pre-generated code to see how it all worked.   The main goal here is to get a grip on how the “has many vs belongs to many” relationships are set up and accessed. Being able to manipulate the model correctly within the conventions the framework is essential for building bug-free and stable apps.</p><Br>

            <p>With this in mind, I set about using the beforeMarshal events which are triggered when new entities are patched or generated.  The beforeMarshal event allows the app to hook any posted data and extract out conceptual policy components from the body and title text.</p><Br>

            <p>These policy components are extracted from text strings, using a little static class helper that parses the text with regex expressions.</p><Br>

            <p>This helper class was originally intended to be a utility class, purely with static helper methods, with a postmortem of the task, there is scope to turn this into class, conceptually holding the extracted data and ensuring the data is formatted correctly.</p><Br>

            <p>The data extraction is 'brute force' and there is no consideration to context.  A potential task to extend the application would be to convert it into proper class and inherit behaviours to allow for contextual replacement.  For example, capitalizing policy components at the start of sentences or in titles.  Allowing for the plural and singular version of each context by infering ‘s’ and ‘ies’ on the end of each tag.  This could all hook into CakePHPs built-in inflector class, which would manage local specific inflexions.</p><Br>

            <h2>Automated Testing and UI</h2>
            <p>For the purpose of efficiency, I have chosen not to focus on setting up unit tests for the application.   That said, reading indicates the CakePHP has a robust unit testing framework, error logging and exception handling.   These all look approachable to extend, develop and use.</p><Br>

            <p>Likewise, the UI is using standard Cake css and is a no-frills UI.  As tempting as it is to look at hooking Ajax calls and Greensock animations, I wanted to focus on getting the database relationships working.</p><Br>
            <p>
            There has been a small exploration into the validation system and the application is currently using the boilerplate form validation. </p><Br>

            <h2>Main Challenges</h2>
            <p>The main challenges of this learning exercise have been getting to grips with CakePHPs object-relational model.   As it is heavily convention-based, so it is easy to put the model in a state where you have an irregular bug.   There where a few 'gotcha moments' I found during development.  Firstly ensure you had  ‘$_accessible’ defined correctly at the Entity level, as this can cause strange problems when you add fields to your database if you forget to set the $_accessible flags.</p><Br>

            <p>The other 'gotcha', is understanding CakePHPs “_ids” field.   If you have a table with many to many relationships, you can simply pass an array of relations with “_ids” fields to your PathEntity method.   This shorthand saves propagating the model data/structure for any unchanged relationship data.  For example, users linked to a policy.</p><Br>

            <p>The 'gotcha' is when you start binding your own data to these relationships.   You have to first unpack the “_ids” field and distribute them to array containers for example:</p>
            <Br>
        
<div class="row">
    <div class="column">
        <div class="message default">
            <small>
$data = [<Br>
<tab to=t1>'title' => 'Policy Title',<Br>
<tab to=t1>'body' => 'Policy body with #tags',<Br>
<tab to=t1>'user_id' => 1,<Br>
<tab to=t1>'tags' => [<Br>
<tab to=t1><tab to=t1>'_ids' => [1, 2, 3, 4]<Br>
<tab to=t1>    ]<Br>
];<Br></small>
        </div>
    </div>
</div>

Has to become:

<div class="row">
    <div class="column">
        <div class="message default">
            <small>
$data = [<Br>
    'title' => 'Policy Title',<Br>
    'body' => ' Policy body with #tags',<Br>
    'user_id' => 1,<Br>
    'policy_components' => [<Br>
        ['id' => 1],<Br>
        ['id' => 2]'<Br>
        ['id' => 3]'<Br>
        ['id' => 4]<Br>
    ]<Br>
];<Br></small>
        </div>
    </div>
</div>


This allows you to bind additional data to the entity as follows:
<div class="row">
    <div class="column">
        <div class="message default">
            <small>
$data = [<Br>
    'title' => 'Policy Title',<Br>
    'body' => ' Policy body with #tags ',<Br>
    'user_id' => 1,<Br>
    'policy_components' => [<Br>
        ['id' => 1],<Br>
        ['id' => 2]'<Br>
        ['id' => 3]'<Br>
	['value' => '#officeSite'],<Br>
	['value' => '#PHPCoder42']<Br>
    ]<Br>
];<Br></small>
        </div>
    </div>
</div>

<p>The correct convention is well documented and CakePHP’s documentation is very good as well as extensive.   When faced with time constraints, small things like this can be 'documentation needles' and it is only after few hours of step-debugging that you get the 'Eureka moment!'</p>
<Br>
<h2>Learning Progress and Conclusions</h2>
<p>I feel comfortable with the progress of learning, being fresh to CakePHP this week.  The majority of the work was done Friday am to Monday am.  I would say roughly 10-12 hours a day and I have established a solid foundation.   You will find I have implemented a global define ("LEARNING_OUTPUT", false) on the AppController which I used to wrap volatile debugging code.  This code has been wrapped in if(Configure::read('learningOutput')) {}.  That, combined with my '#note comments' will give you an idea of how I explored the framework.</p>
<Br>
<p>
    Some of the simpler problems encountered were implementing the password authenticator before hashing my passwords 😊, so it was refusing unhashed passwords even though passwords were saved as plain text.   Turns out the CakePHP uses default hashing when comparing the stored passwords! 
</p><Br>   

<p>Using  entity->extract(schema) to extract the object data during the beforeMarshell event was a useful and happy find.  It allowed me to build and extract entity data as it was being passed to the patching process.</p><Br>

<p>Given the app produced, I am happy to take on a specific task or feature you would like to see, to demonstrate working to spec on task. Overall I am happy with mini-App and its something I will certainly do as a mini project.</p><Br>

<h2>SQL Create Tables</h2>



<div class="row">
    <div class="column">
        <div class="message default">
            <small>
CREATE TABLE `policies` (<Br>
  `id` int(11) NOT NULL AUTO_INCREMENT,<Br>
  `user_id` int(11) DEFAULT NULL,<Br>
  `title` varchar(255) DEFAULT NULL,<Br>
  `body` text,<Br>
  `created` datetime DEFAULT NULL,<Br>
  `modified` datetime DEFAULT NULL,<Br>
  `version` varchar(10) DEFAULT NULL,<Br>
  `approved` date DEFAULT NULL,<Br>
  `expiry` date DEFAULT NULL,<Br>
  PRIMARY KEY (`id`),<Br>
  KEY `user_id` (`user_id`),<Br>
  CONSTRAINT `policies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)<Br>
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;<Br>
</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="column">
        <div class="message default">
            <small>
CREATE TABLE `users` (<Br>
  `id` int(11) NOT NULL AUTO_INCREMENT,<Br>
  `name` varchar(255) DEFAULT NULL,<Br>
  `username` varchar(255) DEFAULT NULL,<Br>
  `email` varchar(255) DEFAULT NULL,<Br>
  `password` varchar(255) DEFAULT NULL,<Br>
  `created` datetime DEFAULT NULL,<Br>
  `modified` datetime DEFAULT NULL,<Br>
  PRIMARY KEY (`id`)<Br>
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;<Br>
</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="column">
        <div class="message default">
            <small>
CREATE TABLE `policy_components` (<Br>
  `id` int(11) NOT NULL AUTO_INCREMENT,<Br>
  `name` varchar(255) DEFAULT NULL,<Br>
  `description` varchar(255) DEFAULT NULL,<Br>
  `value` varchar(255) DEFAULT NULL,<Br>
  `replacement` varchar(255) DEFAULT NULL,<Br>
  `created` datetime DEFAULT NULL,<Br>
  `modified` datetime DEFAULT NULL,<Br>
  PRIMARY KEY (`id`)<Br>
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;<Br>
</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="column">
        <div class="message default">
            <small>
CREATE TABLE `posts` (<Br>
  `id` int(11) NOT NULL AUTO_INCREMENT,<Br>
  `title` varchar(255) DEFAULT NULL,<Br>
  `body` text,<Br>
  `user_id` int(11) DEFAULT NULL,<Br>
  `created` datetime DEFAULT NULL,<Br>
  `modified` datetime DEFAULT NULL,<Br>
  PRIMARY KEY (`id`),<Br>
  KEY `user_id` (`user_id`),<Br>
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)<Br>
) ENGINE=InnoDB DEFAULT CHARSET=latin1;<Br>
</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="column">
        <div class="message default">
            <small>
CREATE TABLE `editors_policies` (<Br>
  `policy_id` int(11) NOT NULL,<Br>
  `user_id` int(11) NOT NULL,<Br>
  PRIMARY KEY (`policy_id`,`user_id`),<Br>
  KEY `user_id` (`user_id`),<Br>
  CONSTRAINT `editors_policies_ibfk_1` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`id`),<Br>
  CONSTRAINT `editors_policies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)<Br>
) ENGINE=InnoDB DEFAULT CHARSET=latin1;<Br>
</small>
        </div>
    </div>
</div>
<div class="row">
    <div class="column">
        <div class="message default">
            <small>
CREATE TABLE `policy_components_policies` (<Br>
  `policy_id` int(11) NOT NULL,<Br>
  `policy_component_id` int(11) NOT NULL,<Br>
  PRIMARY KEY (`policy_id`,`policy_component_id`),<Br>
  KEY `policy_components_policies_ibfk_2` (`policy_component_id`),<Br>
  CONSTRAINT `policy_components_policies_ibfk_1` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`id`),<Br>
  CONSTRAINT `policy_components_policies_ibfk_2` FOREIGN KEY (`policy_component_id`) REFERENCES `policy_components` (`id`)<Br>
) ENGINE=InnoDB DEFAULT CHARSET=latin1;mall development example that <Br>
</small>
        </div>
    </div>
</div>
            </div>

                <div class="row">
                    <div class="column links">
                        <h3>Getting Started</h3>
                        <a target="_blank" href="https://book.cakephp.org/4/en/">CakePHP Documentation</a>
                        <a target="_blank" href="https://book.cakephp.org/4/en/tutorials-and-examples/cms/installation.html">The 20 min CMS Tutorial</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="column links">
                        <h3>Help and Bug Reports</h3>
                        <a target="_blank" href="irc://irc.freenode.net/cakephp">irc.freenode.net #cakephp</a>
                        <a target="_blank" href="http://cakesf.herokuapp.com/">Slack</a>
                        <a target="_blank" href="https://github.com/cakephp/cakephp/issues">CakePHP Issues</a>
                        <a target="_blank" href="http://discourse.cakephp.org/">CakePHP Forum</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="column links">
                        <h3>Docs and Downloads</h3>
                        <a target="_blank" href="https://api.cakephp.org/">CakePHP API</a>
                        <a target="_blank" href="https://bakery.cakephp.org">The Bakery</a>
                        <a target="_blank" href="https://book.cakephp.org/4/en/">CakePHP Documentation</a>
                        <a target="_blank" href="https://plugins.cakephp.org">CakePHP plugins repo</a>
                        <a target="_blank" href="https://github.com/cakephp/">CakePHP Code</a>
                        <a target="_blank" href="https://github.com/FriendsOfCake/awesome-cakephp">CakePHP Awesome List</a>
                        <a target="_blank" href="https://www.cakephp.org">CakePHP</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="column links">
                        <h3>Training and Certification</h3>
                        <a target="_blank" href="https://cakefoundation.org/">Cake Software Foundation</a>
                        <a target="_blank" href="https://training.cakephp.org/">CakePHP Training</a>
                        <a target="_blank" href="https://certification.cakephp.org/">CakePHP Certification</a>
                    </div>
                </div>
                <!--Old Tables here-->
                
                <div class="row">
                    <div class="column">
                        <div class="message default text-center">
                            <small>The following should confirm that you have everything configured correctly</small>
                        </div>
                        <!-- <div id="url-rewriting-warning" class="alert url-rewriting">
                            <ul>
                                <li class="bullet problem">
                                    URL rewriting is not properly configured on your server.<br />
                                    1) <a target="_blank" href="https://book.cakephp.org/4/en/installation.html#url-rewriting">Help me configure it</a><br />
                                    2) <a target="_blank" href="https://book.cakephp.org/4/en/development/configuration.html#general-configuration">I don't / can't use URL rewriting</a>
                                </li>
                            </ul>
                        </div> -->
                        <?php Debugger::checkSecurityKeys(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="column">
                        <h4>Environment</h4>
                        <ul>
                        <?php if (version_compare(PHP_VERSION, '7.2.0', '>=')) : ?>
                            <li class="bullet success">Your version of PHP is 7.2.0 or higher (detected <?php echo PHP_VERSION ?>).</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP is too low. You need PHP 7.2.0 or higher to use CakePHP (detected <?php echo PHP_VERSION ?>).</li>
                        <?php endif; ?>

                        <?php if (extension_loaded('mbstring')) : ?>
                            <li class="bullet success">Your version of PHP has the mbstring extension loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP does NOT have the mbstring extension loaded.</li>
                        <?php endif; ?>

                        <?php if (extension_loaded('openssl')) : ?>
                            <li class="bullet success">Your version of PHP has the openssl extension loaded.</li>
                        <?php elseif (extension_loaded('mcrypt')) : ?>
                            <li class="bullet success">Your version of PHP has the mcrypt extension loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP does NOT have the openssl or mcrypt extension loaded.</li>
                        <?php endif; ?>

                        <?php if (extension_loaded('intl')) : ?>
                            <li class="bullet success">Your version of PHP has the intl extension loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your version of PHP does NOT have the intl extension loaded.</li>
                        <?php endif; ?>
                        </ul>
                    </div>
                    <div class="column">
                        <h4>Filesystem</h4>
                        <ul>
                        <?php if (is_writable(TMP)) : ?>
                            <li class="bullet success">Your tmp directory is writable.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your tmp directory is NOT writable.</li>
                        <?php endif; ?>

                        <?php if (is_writable(LOGS)) : ?>
                            <li class="bullet success">Your logs directory is writable.</li>
                        <?php else : ?>
                            <li class="bullet problem">Your logs directory is NOT writable.</li>
                        <?php endif; ?>

                        <?php $settings = Cache::getConfig('_cake_core_'); ?>
                        <?php if (!empty($settings)) : ?>
                            <li class="bullet success">The <em><?php echo $settings['className'] ?>Engine</em> is being used for core caching. To change the config edit config/app.php</li>
                        <?php else : ?>
                            <li class="bullet problem">Your cache is NOT working. Please check the settings in config/app.php</li>
                        <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="column">
                        <h4>Database</h4>
                        <?php
                        try {
                            $connection = ConnectionManager::get('default');
                            $connected = $connection->connect();
                        } catch (Exception $connectionError) {
                            $connected = false;
                            $errorMsg = $connectionError->getMessage();
                            if (method_exists($connectionError, 'getAttributes')) :
                                $attributes = $connectionError->getAttributes();
                                if (isset($errorMsg['message'])) :
                                    $errorMsg .= '<br />' . $attributes['message'];
                                endif;
                            endif;
                        }
                        ?>
                        <ul>
                        <?php if ($connected) : ?>
                            <li class="bullet success">CakePHP is able to connect to the database.</li>
                        <?php else : ?>
                            <li class="bullet problem">CakePHP is NOT able to connect to the database.<br /><?php echo $errorMsg ?></li>
                        <?php endif; ?>
                        </ul>
                    </div>
                    <div class="column">
                        <h4>DebugKit</h4>
                        <ul>
                        <?php if (Plugin::isLoaded('DebugKit')) : ?>
                            <li class="bullet success">DebugKit is loaded.</li>
                        <?php else : ?>
                            <li class="bullet problem">DebugKit is NOT loaded. You need to either install pdo_sqlite, or define the "debug_kit" connection name.</li>
                        <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <hr>
                
            </div>
        </div>
    </main>
</body>
</html>
