<?php

namespace InstaMediaFetch;

/**
 * InstaMediaFetch Fetcher
 *
 * Fetch Instagram page and parse content
 */
class Fetcher
{
    private $sharedData = '';

    /**
     * Fetch media from an instagram account
     *
     * @param string $accountName
     * @return Profile|bool
     */
    public function fetchMedia(string $accountName)
    {
        $body = '';
        $body = $this->curlFetch(
            sprintf('https://www.instagram.com/%s', $accountName)
        );
        // No data from curl call
        if ($body === false) {
            return false;
        }

        $this->sharedData = $this->extractSharedData($body);
        // failed to extract shared data
        if ($this->sharedData === false) {
            return false;
        }

        $profile = new Profile();
        $profile = $this->parseProfile($profile);

        // Failed to parse profile
        if ($profile === false) {
            return false;
        }

        $medias = $this->parseMedias();
        $profile->setMedias($medias);

        return $profile;
    }

    /**
     * Parse Instagram profile
     *
     * @param Profile $profile
     * @return Profile|bool
     */
    private function parseProfile(Profile $profile)
    {
        if (
            isset(
                $this->sharedData['entry_data']['ProfilePage'][0]['graphql'][
                    'user'
                ]
            )
        ) {
            $userData =
                $this->sharedData['entry_data']['ProfilePage'][0]['graphql'][
                    'user'
                ];
            $profile->setUsername($userData['username']);
            $profile->setId($userData['id']);
            $profile->setNbFollowers($userData['edge_followed_by']['count']);
            $profile->setNbFollows($userData['edge_follow']['count']);
            $profile->setBiography($userData['biography']);
            $profile->setExternalUrl($userData['external_url']);
            $profile->setProfilePic($userData['profile_pic_url']);
            $profile->setProfilePicHD($userData['profile_pic_url_hd']);

            return $profile;
        }

        return false;
    }

    /**
     * Fetch url content
     *
     * @param string $url
     * @return string|bool
     */
    private function curlFetch($url)
    {
        $curlHandler = curl_init($url);
        if ($curlHandler === false) {
            return false;
        }

        $curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ];
        curl_setopt_array($curlHandler, $curlOptions);
        $curlResponse = curl_exec($curlHandler);

        if ($curlResponse === false) {
            return false;
        }
        return $curlResponse;
    }

    /**
     * Parse media entities from shared data
     *
     * @return array
     */
    private function parseMedias(): array
    {
        $medias = [];
        if (
            isset(
                $this->sharedData['entry_data']['ProfilePage'][0]['graphql'][
                    'user'
                ]['edge_owner_to_timeline_media']['edges']
            )
        ) {
            foreach (
                $this->sharedData['entry_data']['ProfilePage'][0]['graphql'][
                    'user'
                ]['edge_owner_to_timeline_media']['edges']
                as $edgeMedia
            ) {
                $edgeMedia = $edgeMedia['node'];
                $media = new Media();
                $media->setCaption(
                    $edgeMedia['edge_media_to_caption']['edges'][0]['node'][
                        'text'
                    ]
                );

                $media->setWidth($edgeMedia['dimensions']['width']);
                $media->setHeight($edgeMedia['dimensions']['height']);
                $media->setShortCode($edgeMedia['shortcode']);
                $media->setTimestamp($edgeMedia['taken_at_timestamp']);
                $media->setNbLikes($edgeMedia['edge_liked_by']['count']);
                $media->setNbComments(
                    $edgeMedia['edge_media_to_comment']['count']
                );
                $media->setUrl($edgeMedia['display_url']);
                foreach ($edgeMedia['thumbnail_resources'] as $thumbnail) {
                    $media->addThumbnail(
                        $thumbnail['config_width'],
                        $thumbnail['config_height'],
                        $thumbnail['src']
                    );
                }

                $medias[] = $media;
            }
        }

        return $medias;
    }

    /**
     * Extract js shared data from page content
     *
     * @param string $pageBody
     * @return array|null
     */
    private function extractSharedData($pageBody): ?array
    {
        if (
            preg_match_all(
                '#\_sharedData \= (.*?)\;\<\/script\>#',
                $pageBody,
                $out
            )
        ) {
            return json_decode($out[1][0], true, 512, JSON_BIGINT_AS_STRING);
        }
        return null;
    }
}
