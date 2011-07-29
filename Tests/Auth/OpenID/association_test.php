<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/common.php');

require_once 'Auth/OpenID/Association.php';


class Tests_Auth_OpenID_Association extends UnitTestCase {
    function Tests_Auth_OpenID_Association() {
        $this->UnitTestCase( 'Tests_Auth_OpenID_Association' );
    }

    function test_me() {
        $issued = time();
        $lifetime = 600;
        $assoc = new Auth_OpenID_Association('handle', 'secret', $issued, $lifetime, 'HMAC-SHA1');
        $s = $assoc->serialize();
        $assoc2 = Auth_OpenID_Association::deserialize('Auth_OpenID_Association', $s);

        if ($assoc2 === null) {
            $this->fail('deserialize returned null');
        }
        else {
            $this->assertTrue($assoc2->equal($assoc));
        }
    }

    function test_me256() {
        if (!Auth_OpenID_HMACSHA256_SUPPORTED) {
            return;
        }
        $issued = time();
        $lifetime = 600;
        $assoc = new Auth_OpenID_Association('handle', 'secret', $issued, $lifetime, 'HMAC-SHA256');
        $s = $assoc->serialize();
        $assoc2 = Auth_OpenID_Association::deserialize('Auth_OpenID_Association', $s);

        if ($assoc2 === null) {
            $this->fail('deserialize returned null');
        }
        else {
            $this->assertTrue($assoc2->equal($assoc));
        }
    }
}
