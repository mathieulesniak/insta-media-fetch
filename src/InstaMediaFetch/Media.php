<?php
namespace InstaMediaFetch;

class Media
{
    /**
     * @var string $caption Media caption
     */
    private $caption;

    /**
     * @var int $timestamp Media taken at timestamp
     */
    private $timestamp;

    /**
     * @var int $width Media width
     */
    private $width = 0;

    /**
     * @var int $height Media height
     */
    private $height = 0;

    /**
     * @var int $nbLikes Media number of likes
     */
    private $nbLikes = 0;

    /**
     * @var int $nbComments Media number of comments
     */
    private $nbComments = 0;

    /**
     * @var string $shortCode Media public ID
     */
    private $shortCode = '';

    /**
     * @var string $url Media url
     */
    private $url = '';

    /**
     * @var array $thumbnails Media thumbnails list
     */
    private $thumbnails = [];

    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getDateTime($format = 'd/m/Y H:i:s')
    {
        return date($format, $this->timestamp);
    }

    public function setWidth(int $width): Media
    {
        $this->width = $width;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setHeight(int $height): Media
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setNbLikes(int $nbLikes): Media
    {
        $this->nbLikes = $nbLikes;
        return $this;
    }

    public function getNbLikes(): int
    {
        return $this->nbLikes;
    }

    public function setNbComments(int $nbComments): Media
    {
        $this->nbComments = $nbComments;
        return $this;
    }

    public function getNbComments(): int
    {
        return $this->nbComments;
    }

    public function setShortcode(string $shortCode): Media
    {
        $this->shortCode = $shortCode;
        return $this;
    }

    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    public function setUrl(string $url): Media
    {
        $this->url = $url;
        return $this;
    }
    public function getUrl(): string
    {
        return $this->url;
    }

    public function addThumbnail($width, $height, $url): Media
    {
        $this->thumbnails[$width . 'x' . $height] = $url;
        return $this;
    }

    public function getThumbnails(): array
    {
        return $this->thumbnails;
    }
}
