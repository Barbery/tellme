<?php

namespace Barbery\TellMe\Provider;

class EmailProvider extends ServiceProvider
{
    public function send()
    {
        try {
            $Mailer = new \PHPMailer();
            if (!empty($this->channel['is_smtp'])) {
                $Mailer->isSMTP();
            }

            if (!empty($this->channel['is_html'])) {
                $Mailer->isHTML(true);
            }

            $Mailer->Host       = $this->get('host');
            $Mailer->SMTPAuth   = $this->get('smtp_auth');
            $Mailer->Username   = $this->get('username');
            $Mailer->Password   = $this->get('password');
            $Mailer->SMTPSecure = $this->get('smtp_secure');
            $Mailer->Port       = $this->get('port');
            $Mailer->setFrom($this->get('username'));
            foreach ($this->get('to', []) as $email) {
                $Mailer->addAddress($email);
            }

            $Mailer->Subject = $this->translatedData['title'];
            $Mailer->Body    = $this->translatedData['content'];
            $Mailer->AltBody = strip_tags($this->translatedData['content']);

            dd($Mailer->send(), $Mailer->ErrorInfo);
        } catch (\Exception $e) {
            return false;
        }
    }
}
