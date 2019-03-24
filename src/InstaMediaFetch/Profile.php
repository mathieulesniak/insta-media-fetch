<?php
namespace InstaMediaFetch;

/**
 * Profile class
 * contains profile data + medias
 */
class Profile
{
    /**
     * @var string $userId Profile unique ID
     */
    protected $userId = "0";

    /**
     * @var string $username Profile username
     */
    protected $username;

    /**
     * @var int $nbFollowers Profile number of followers
     */
    protected $nbFollowers = 0;

    /**
     * @var int $nbFollows Profile number of followed accounts
     */
    protected $nbFollows = 0;

    /**
     * @var string $biography Profile biography
     */
    protected $biography = '';

    /**
     * @var string $externalUrl Profile profile external URL
     */
    protected $externalUrl = '';

    /**
     * @var string $profilePic Profile picture
     */
    protected $profilePic = '';

    /**
     * @var string $profilePicHSD Profile picture HS
     */
    protected $profilePicHD = '';

    /**
     * @var array $medias Profile medias list
     */
    protected $medias;

    public function setId(string $userId): Profile
    {
        $this->userId = $userId;
        return $this;
    }

    public function getId(): string
    {
        return $this->userId;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function addMedia(Media $media)
    {
        $this->medias[] = $media;
        return $this;
    }

    public function setMedias($medias)
    {
        $this->medias = $medias;
        return $this;
    }

    public function getMedias()
    {
        return $this->medias;
    }

    public function setNbFollowers(int $followers): Profile
    {
        $this->nbFollowers = $followers;
        return $this;
    }

    public function getNbFollowers(): int
    {
        return $this->nbFollowers;
    }

    public function setNbFollows(int $follows): Profile
    {
        $this->nbFollows = $follows;
        return $this;
    }

    public function getNbFollows(): int
    {
        return $this->nbFollows;
    }

    public function setBiography(string $bio): Profile
    {
        $this->biography = $bio;
        return $this;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setExternalUrl(string $url): Profile
    {
        $this->externalUrl = $url;
        return $this;
    }

    public function getExternalUrl(): string
    {
        return $this->externalUrl;
    }

    public function setProfilePic(string $url): Profile
    {
        $this->profilePic = $url;
        return $this;
    }

    public function getProfilePic(): string
    {
        return $this->profilePic;
    }

    public function setProfilePicHD(string $url): Profile
    {
        $this->profilePicHD = $url;
        return $this;
    }

    public function getProfilePicHD(): string
    {
        return $this->profilePicHD;
    }
}
