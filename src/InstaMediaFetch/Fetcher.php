<?php
namespace InstaMediaFetch;

class Fetcher
{
    private $sharedData = '';

    public function fetchMedia($accountName): Profile
    {
        $body = '';
        $body = $this->curlFetch('https://www.instagram.com/' . $accountName);

        $this->sharedData = $this->extractSharedData($body);
        $profile = new Profile();
        $profile = $this->parseProfile($profile);
        $medias = $this->parseMedias();
        $profile->setMedias($medias);

        return $profile;
    }

    private function parseProfile(Profile $profile): Profile
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
        throw new \Exception("Can't find user in shared data");
    }

    private function curlFetch($url)
    {
        $curlHandler = curl_init($url);
        if ($curlHandler === false) {
            throw new \Exception("Can't init curl");
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

    private function parseMedias()
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
