<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/common.php');

require_once 'Tests/Auth/OpenID/test_util.php';

require_once 'Auth/OpenID/Association.php';
require_once 'Auth/OpenID/Consumer.php';

class AuthRequest_DummyEndpoint {
    var $preferred_namespace = null;
    var $local_id = null;
    var $server_url = null;
    var $is_op_identifier = false;

    function preferredNamespace() {
        return $this->preferred_namespace;
    }

    function getLocalID() {
        return $this->local_id;
    }

    function isOPIdentifier() {
        return $this->is_op_identifier;
    }
}

class AuthRequest_DummyAssoc {
    var $handle = "assoc-handle";
}

/**
 * Base for AuthRequest tests for OpenID 1 and 2.
 */
class TestAuthRequestMixin extends OpenIDTestMixin {

    var $preferred_namespace = null;
    var $immediate = false;
    var $expected_mode = 'checkid_setup';

    function setUp() {
        $this->endpoint = new AuthRequest_DummyEndpoint();
        $this->endpoint->local_id = 'http://server.unittest/joe';
        $this->endpoint->claimed_id = 'http://joe.vanity.example/';
        $this->endpoint->server_url = 'http://server.unittest/';
        $this->endpoint->preferred_namespace = $this->preferred_namespace;
        $this->realm = 'http://example/';
        $this->return_to = 'http://example/return/';
        $this->assoc = new AuthRequest_DummyAssoc();
        $this->authreq = new Auth_OpenID_AuthRequest($this->endpoint, $this->assoc);
    }

    function failUnlessAnonymous($msg) {
        foreach (array('claimed_id', 'identity') as $key) {
            $this->failIfOpenIDKeyExists($msg, $key);
        }
    }

    function failUnlessHasRequiredFields($msg) {
        $this->assertEqual($this->preferred_namespace, $this->authreq->message->getOpenIDNamespace());

        $this->assertEqual($this->preferred_namespace, $msg->getOpenIDNamespace());

        $this->failUnlessOpenIDValueEquals($msg, 'mode', $this->expected_mode);

        // Implement these in subclasses because they depend on
        // protocol differences!
        $this->failUnlessHasRealm($msg);
        $this->failUnlessIdentifiersPresent($msg);
    }

    // TESTS
    function test_checkNoAssocHandle() {
        $this->authreq->assoc = null;
        $msg = $this->authreq->getMessage($this->realm, $this->return_to, $this->immediate);

        $this->failIfOpenIDKeyExists($msg, 'assoc_handle');
    }

    function test_checkWithAssocHandle() {
        $msg = $this->authreq->getMessage($this->realm, $this->return_to, $this->immediate);

        $this->failUnlessOpenIDValueEquals($msg, 'assoc_handle', $this->assoc->handle);
    }

    // function test_addExtensionArg() {
        // $this->authreq->addExtensionArg('bag:', 'color', 'brown');
        // $this->authreq->addExtensionArg('bag:', 'material', 'paper');
        // $this->assertTrue($this->authreq->message->namespaces->contains('bag:'));
        // $this->assertEquals($this->authreq->message->getArgs('bag:'), array('color' => 'brown', 'material' => 'paper'));
        // $msg = $this->authreq->getMessage($this->realm, $this->return_to, $this->immediate);

        // // XXX: this depends on the way that Message assigns
        // // namespaces. Really it doesn't care that it has alias "0",
        // // but that is tested anyway
        // $post_args = $msg->toPostArgs();
        // $this->assertEquals('brown', $post_args['openid.ext0.color']);
        // $this->assertEquals('paper', $post_args['openid.ext0.material']);
    // }

    // function test_standard() {
        // $msg = $this->authreq->getMessage($this->realm, $this->return_to, $this->immediate);

        // $this->failUnlessHasIdentifiers($msg, $this->endpoint->local_id, $this->endpoint->claimed_id);
    // }
}
// class hoge extends UnitTestCase {
// function hoge() {
// $this->UnitTestCase('hoge');
// }
// function setup() {
// }
// }
