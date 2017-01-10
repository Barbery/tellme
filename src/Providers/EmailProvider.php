<?php

namespace Barbery\TellMe\Provider;

class EmailProvider extends ServiceProvider
{
    public function send()
    {
        $mail = new PHPMailer;
        if (!emtpy($this->channel['is_smtp'])) {
            $mail->isSMTP();
        }

        if (!emtpy($this->channel['is_html'])) {
            $mail->isHTML(true);
        }

        $mail->Host       = $this->get('host');
        $mail->SMTPAuth   = $this->get('smtp_auth');
        $mail->Username   = $this->get('username');
        $mail->Password   = $this->get('password');
        $mail->SMTPSecure = $this->get('smtp_secure');
        $mail->Port       = $this->get('port');

        $mail->setFrom('from@example.com', 'Mailer');
        foreach ($this->get('to', []) as $email) {
            $mail->addAddress($email);
        }

        $mail->Subject = $this->translatedData['title'];
        $mail->Body    = $this->translatedData['content'];
        $mail->AltBody = strip_tags($this->translatedData['content']);

        return $mail->send();
    }
}
