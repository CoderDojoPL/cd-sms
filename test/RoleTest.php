<?php
/*
 * This file is part of the HMS project.
 *
 * (c) CoderDojo Polska Foundation
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test;
require_once __DIR__ . '/../common/WebTestCaseHelper.php';

use Common\WebTestCaseHelper;


/**
 * @package Test
 * @author Sławek Nowak (s.nowak@coderdojo.org.pl)
 */
class RoleTest extends WebTestCaseHelper
{
    public function testIndexUnautheticate()
    {

        $client = $this->createClient();
        $url = $client->loadPage('/role')
            ->getUrl();

        $this->assertEquals('/login', $url);

    }
}