<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App_Model_EventRepository") 
 * @Table(name="events")
 */
class App_Model_Event
{
	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;
	/** @Column(type="string") */
	protected $title;
	/** @Column(type="string", length=500) */
	protected $description;
	/** @Column(type="datetime", name="start_date") */
	protected $startDate;
	/** @Column(type="integer") */
	protected $published;
	/**
	 * @ManyToMany(targetEntity="App_Model_User")
	 * @JoinTable(name="attendance",
	 *     joinColumns={@JoinColumn(name="event_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")} 
	 * )
	 */
	private $attendees;
	/** @Column(type="string") */
	protected $mapLink;
	/** @Column(type="string") */
	protected $mapText;

	public function __construct()
	{
		$this->attendees = new ArrayCollection;
	}

	public function getMapLink()
	{
		return $this->mapLink;
	}

	public function setMapLink($mapLink)
	{
		$this->mapLink = $mapLink;
	}

	public function getMapText()
	{
		return $this->mapText;
	}

	public function setMapText($mapText)
	{
		$this->mapText = $mapText;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function setStartDate($startDate)
	{
		$this->startDate = $startDate;
	}

	public function setPublished($published)
	{
		$this->published = $published;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function getPublished()
	{
		return $this->published;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getAttendees()
	{
		return $this->attendees;
	}
}
