<?php

namespace Accord\MandrillSwiftMailer\Tests\SwiftMailer;

use Accord\MandrillSwiftMailer\SwiftMailer\MandrillTransport;

class MandrillTransportTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Swift_Events_EventDispatcher
     */
    protected $dispatcher;

    protected function setUp()
    {
        $this->dispatcher = $this->getMock('\Swift_Events_EventDispatcher');
    }

    /**
     * Returns an instance of the transport through which test messages can be sent
     *
     * @return MandrillTransport
     */
    protected function createTransport()
    {
        $transport = new MandrillTransport($this->dispatcher);
        $transport->setApiKey('AexOlO8l1E1JE_7jEXbSpQ');
        return $transport;
    }

    public function testTags()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', 'Foo bar');

        $message
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
        ;

        $message->getHeaders()->addTextHeader('X-MC-Tags', 'foo,bar');

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertEquals('foo', $mandrillMessage['tags'][0]);
        $this->assertEquals('bar', $mandrillMessage['tags'][1]);

        $this->assertMessageSendable($message);
    }

    public function testMultipartNullContentType()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', 'Foo bar');

        $message
            ->addPart('<p>Foo bar</p>', 'text/html')
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
        ;

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertEquals('Foo bar', $mandrillMessage['text'], 'Multipart email should contain plaintext message');
        $this->assertEquals('<p>Foo bar</p>', $mandrillMessage['html'], 'Multipart email should contain HTML message');

        $this->assertMessageSendable($message);

    }

    public function testMultipartPlaintextFirst()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', 'Foo bar', 'text/plain');

        $message
            ->addPart('<p>Foo bar</p>', 'text/html')
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
        ;

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertEquals('Foo bar', $mandrillMessage['text'], 'Multipart email should contain plaintext message');
        $this->assertEquals('<p>Foo bar</p>', $mandrillMessage['html'], 'Multipart email should contain HTML message');

        $this->assertMessageSendable($message);
    }

    public function testMultipartHtmlFirst()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', '<p>Foo bar</p>', 'text/html');

        $message
            ->addPart('Foo bar', 'text/plain')
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
        ;

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertEquals('Foo bar', $mandrillMessage['text'], 'Multipart email should contain plaintext message');
        $this->assertEquals('<p>Foo bar</p>', $mandrillMessage['html'], 'Multipart email should contain HTML message');

        $this->assertMessageSendable($message);
    }

    public function testPlaintextMessage()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', 'Foo bar', 'text/plain');

        $message
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
        ;

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertNull($mandrillMessage['html'], 'Plaintext only email should not contain HTML counterpart');
        $this->assertEquals('Foo bar', $mandrillMessage['text']);

        $this->assertMessageSendable($message);
    }

    public function testHtmlMessage()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', '<p>Foo bar</p>', 'text/html');

        $message
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
        ;

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertNull($mandrillMessage['text'], 'HTML only email should not contain plaintext counterpart');
        $this->assertEquals('<p>Foo bar</p>', $mandrillMessage['html']);

        $this->assertMessageSendable($message);
    }

    public function testMessage()
    {
        $transport = $this->createTransport();

        $message = new \Swift_Message('Test Subject', '<p>Foo bar</p>', 'text/html');

        $attachment = new \Swift_Attachment('FILE_CONTENTS', 'filename.txt', 'text/plain');
        $message->attach($attachment);

        $message
            ->addTo('to@example.com', 'To Name')
            ->addFrom('from@example.com', 'From Name')
            ->addCc('cc-1@example.com', 'CC 1 Name')
            ->addCc('cc-2@example.com', 'CC 2 Name')
            ->addBcc('bcc-1@example.com', 'BCC 1 Name')
            ->addBcc('bcc-2@example.com', 'BCC 2 Name')
            ->addReplyTo('reply-to@example.com', 'Reply To Name')
        ;

        $mandrillMessage = $transport->getMandrillMessage($message);

        $this->assertEquals('<p>Foo bar</p>', $mandrillMessage['html']);
        $this->assertNull($mandrillMessage['text'], 'HTML only email should not contain plaintext counterpart');
        $this->assertEquals('Test Subject', $mandrillMessage['subject']);
        $this->assertEquals('from@example.com', $mandrillMessage['from_email']);
        $this->assertEquals('From Name', $mandrillMessage['from_name']);

        $this->assertMandrillMessageContainsRecipient('to@example.com', 'To Name', 'to', $mandrillMessage);
        $this->assertMandrillMessageContainsRecipient('cc-1@example.com', 'CC 1 Name', 'cc', $mandrillMessage);
        $this->assertMandrillMessageContainsRecipient('cc-2@example.com', 'CC 2 Name', 'cc', $mandrillMessage);
        $this->assertMandrillMessageContainsRecipient('bcc-1@example.com', 'BCC 1 Name', 'bcc', $mandrillMessage);
        $this->assertMandrillMessageContainsRecipient('bcc-2@example.com', 'BCC 2 Name', 'bcc', $mandrillMessage);

        $this->assertMandrillMessageContainsAttachment('text/plain', 'filename.txt', 'FILE_CONTENTS', $mandrillMessage);

        $this->assertArrayHasKey('Reply-To', $mandrillMessage['headers']);
        $this->assertEquals('reply-to@example.com <Reply To Name>', $mandrillMessage['headers']['Reply-To']);

        $this->assertMessageSendable($message);
    }

    /**
     * @param string $type
     * @param string $name
     * @param string $content
     * @param array $message
     */
    protected function assertMandrillMessageContainsAttachment($type, $name, $content, array $message){
        foreach($message['attachments'] as $attachment){
            if($attachment['type'] === $type && $attachment['name'] === $name){
                $this->assertEquals($content, base64_decode($attachment['content']));
                return;
            }
        }
        $this->fail(sprintf('Expected Mandrill message to contain a %s attachment named %s', $type, $name));
    }

    /**
     * @param string $email
     * @param string $name
     * @param string $type
     * @param array $message
     */
    protected function assertMandrillMessageContainsRecipient($email, $name, $type, array $message){
        foreach($message['to'] as $recipient){
            if($recipient['email'] === $email && $recipient['name'] === $name && $recipient['type'] === $type){
                $this->assertTrue(true);
                return;
            }
        }
        $this->fail(sprintf('Expected Mandrill message "to" contain %s recipient %s <%s>', $type, $email, $name));
    }

    /**
     * Performs a test send through the Mandrill API. Provides details of failure if there are any problems.
     *
     * @param \Swift_Message $message
     */
    protected function assertMessageSendable(\Swift_Message $message)
    {
        $transport = $this->createTransport();
        $result = $transport->send($message);
        $resultApi = $transport->getResultApi();

        if(count($resultApi) === 0 || $result === 0){
            $this->fail(sprintf(
                'Expected at least one email to be processed by Mandrill Test API (%s items in API response, %s reported as sent by transport)',
                count($resultApi),
                $result
            ));
        }

        foreach($resultApi as $item){
            $this->assertResultApiItemQueuedOrSent($item);
        }


    }

    protected function assertResultApiItemQueuedOrSent(array $item)
    {
        $status = (isset($item['status']) ? $item['status'] : 'unknown_status');
        $reason = (isset($item['reject_reason']) ? $item['reject_reason'] : 'unknown_reason');

        if($status !== 'queued' && $status !== 'sent'){
            $this->fail(
                sprintf(
                    'Mandrill Test API could not process message (%s: %s)',
                    $status,
                    $reason
                )
            );
        }
    }



}