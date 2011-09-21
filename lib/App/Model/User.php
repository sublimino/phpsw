<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="users")
 */
class App_Model_User
{
	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;
	/** @Column(type="string") */
	protected $twitterUsername;
	/** @Column(type="string") */
	protected $twitterId;
	/**
	 * @ManyToMany(targetEntity="App_Model_Event", mappedBy="attendees")
	 */
	protected $events;
	/** @Column(type="string") */
	protected $accessToken;
	/** @Column(type="string") */
	protected $accessSecret;
	/** @Column(type="datetime") */
	protected $lastLogin;
	/** @Column(type="boolean") */
	protected $isAdmin;

	public function __construct()
	{
		$this->events = new ArrayCollection;
	}

	public function isAdmin()
	{
		return $this->isAdmin;
	}

	public function setIsAdmin($isAdmin)
	{
		$this->isAdmin = $isAdmin;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTwitterUsername()
	{
		return $this->twitterUsername;
	}

	public function setTwitterUsername($twitterUsername)
	{
		$this->twitterUsername = $twitterUsername;
	}

	public function getTwitterId()
	{
		return $this->twitterId;
	}

	public function setTwitterId($twitterId)
	{
		$this->twitterId = $twitterId;
	}

	public function setLastLogin(DateTime $lastLogin)
	{
		$this->lastLogin = $lastLogin;
	}

	public function setAccessToken($accessToken)
	{
		$this->accessToken = $accessToken;
	}

	public function setAccessSecret($accessSecret)
	{
		$this->accessSecret = $accessSecret;
	}

	public function getAccessSecret()
	{
		return $this->accessSecret;
	}

	public function getEvents()
	{
		return $this->events;
	}
}
