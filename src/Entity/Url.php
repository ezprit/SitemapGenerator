<?php

namespace SitemapGenerator\Entity;

/**
 * Represents a sitemap entry.
 *
 * @see http://www.sitemaps.org/protocol.html
 */
class Url
{
    /**
     * URL of the page.
     * Should NOT begin with the protocol (as it will be added later) but MUST
     * end with a trailing slash, if your web server requires it. This value
     * must be less than 2,048 characters.
     */
    protected $loc = null;

    /**
     * The date of last modification of the file. This date should be in W3C
     * Datetime format. This format allows you to omit the time portion, if
     * desired, and use YYYY-MM-DD.
     *
     * NOTE This tag is separate from the If-Modified-Since (304) header
     * the server can return, and search engines may use the information from
     * both sources differently.
     *
     * @var \DateTime
     */
    protected $lastmod = null;

    /**
     * How frequently the page is likely to change. This value provides general
     * information to search engines and may not correlate exactly to how often
     * they crawl the page.
     * Valid values are represented as class constants.
     */
    protected $changefreq = null;

    /**
     * The priority of this URL relative to other URLs on your site. Valid
     * values range from 0.0 to 1.0. This value does not affect how your pages
     * are compared to pages on other sites—it only lets the search engines
     * know which pages you deem most important for the crawlers.
     *
     * The default priority of a page is 0.5 (if not set in the sitemap).
     */
    protected $priority = null;

    protected $videos = [];

    protected $images = [];

    /**
     * @see http://www.sitemaps.org/protocol.html#escaping
     *
     * @param string $loc The location. Must be less than 2,048 chars.
     */
    public function setLoc($loc)
    {
        if (strlen($loc) > 2048) {
            throw new \DomainException('The loc value must be less than 2,048 characters');
        }

        $this->loc = $loc;

        return $this;
    }

    public function getLoc()
    {
        return $this->loc;
    }

    public function setLastmod($lastmod)
    {
        if ($lastmod !== null && !$lastmod instanceof \DateTime) {
            $lastmod = new \DateTime($lastmod);
        }

        $this->lastmod = $lastmod;

        return $this;
    }

    public function getLastmod()
    {
        if ($this->lastmod === null) {
            return null;
        }

        if ($this->getChangefreq() === null || in_array($this->getChangefreq(), [ChangeFrequency::ALWAYS, ChangeFrequency::HOURLY], true)) {
            return $this->lastmod->format(\DateTime::W3C);
        }

        return $this->lastmod->format('Y-m-d');
    }

    public function setChangefreq($changefreq)
    {
        $validFreqs = [
            ChangeFrequency::ALWAYS, ChangeFrequency::HOURLY, ChangeFrequency::DAILY,
            ChangeFrequency::WEEKLY, ChangeFrequency::MONTHLY, ChangeFrequency::YEARLY,
            ChangeFrequency::NEVER,
        ];

        if ($changefreq !== null && !in_array($changefreq, $validFreqs, true)) {
            throw new \DomainException(sprintf('Invalid changefreq given. Valid values are: %s', implode(', ', $validFreqs)));
        }

        $this->changefreq = $changefreq;

        return $this;
    }

    public function getChangefreq()
    {
        return $this->changefreq;
    }

    public function setPriority($priority)
    {
        $priority = (float) $priority;

        if ($priority < 0 || $priority > 1) {
            throw new \DomainException('The priority must be between 0 and 1');
        }

        $this->priority = $priority;

        return $this;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function addVideo(Video $video)
    {
        $this->videos[] = $video;

        return $this;
    }

    public function setVideos(array $videos)
    {
        $this->videos = $videos;

        return $this;
    }

    /**
     * @return Video[]
     */
    public function getVideos()
    {
        return $this->videos;
    }

    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    public function setImages(array $images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->images;
    }
}
