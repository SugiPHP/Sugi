<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

class Mail
{
	public static $from;
	public static $returnTo;

	/**
	 * @var Swift_Mailer instance
	 */
	protected static $mailer;

	/**
	 * Returns the singleton instance of the CssPacker class
	 */
	public static function getInstance()
	{
		if (!static::$mailer) {
			static::$mailer = static::factory(Config::get("mailer"));
			if ($from = Config::get("mailer.from")) {
				static::$from = $from;
			}
			if ($returnTo = Config::get("mailer.returnTo")) {
				static::$returnTo = $returnTo;
			}
		}

		return static::$mailer;
	}

	public static function factory(array $config)
	{
		$transportType = empty($config["transport"]) ? "mail" : $config["transport"];
		$transportConfig = isset($config[$transportType]) ? $config[$transportType] : array();

		if ($transportType == "mail") {
			// Mail
			$transport = \Swift_MailTransport::newInstance();
		} elseif ($transportType == "sendmail") {
			// Sendmail
			$transport = \Swift_SendmailTransport::newInstance($transportConfig["path"]);
		} elseif ($transportType == "smtp") {
			// SMTP
			$transport = \Swift_SmtpTransport::newInstance($transportConfig["host"], isset($transportConfig["port"]) ? $transportConfig["port"] : 25);
			if (isset($transportConfig["username"])) {
				$transport->setUsername($transportConfig["username"]);
			}
			if (isset($transportConfig["username"])) {
				$transport->setPassword($transportConfig["username"]);
			}
		}

		// Create the Mailer using your created Transport
		$mailer = \Swift_Mailer::newInstance($transport);

		return $mailer;
	}

	public static function send($to, $subject, $body, $html = null, $from = null)
	{
		if (is_null($from)) {
			$from = static::$from;
		}

		$to = (array) $to;
		
		// Create the message
		$message = \Swift_Message::newInstance()
			// Give the message a subject
			->setSubject($subject)
			// Set the From address with an associative array
			->setFrom($from)
			// Set the To addresses with an associative array
			->setTo($to)
			// Give it a body
			->setBody($body);

		// And optionally an alternative body
		if (!is_null($html)) {
			$message->addPart($html, "text/html");
		}

		if (static::$returnTo) {
			$message->setReturnPath(static::$returnTo);
		}

		// Optionally add any attachments
		//->attach(Swift_Attachment::fromPath('my-document.pdf'))

		$mailer = static::getInstance();

		return $mailer->send($message);
	}
}
