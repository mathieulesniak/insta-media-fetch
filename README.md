# insta-media-fetch

Fetch profile informations and last pictures of a public profile

## Usage

```php
<?php
$fetcher = new InstaMediaFetch\Fetcher();
$profile = $fetcher->fetchMedia('natgeo');

echo "Account ID: " . $profile->getId() . PHP_EOL;
echo "Username: " . $profile->getUsername() . PHP_EOL;
echo "Nb followers: " . $profile->getNbFollowers() . PHP_EOL;
echo "Nb followed accounts: " . $profile->getNbFollows() . PHP_EOL;
echo "Biography: " . $profile->getBiography() . PHP_EOL;
echo "Site URL: " . $profile->getExternalUrl() . PHP_EOL;
echo "Profile Pic: " . $profile->getProfilePic() . PHP_EOL;
echo "Profile Pic HD: " . $profile->getProfilePicHD() . PHP_EOL;

echo "Medias : " . PHP_EOL;
foreach ($profile->getMedias() as $offset => $media) {
    echo "MEDIA #" . $offset . ":" . PHP_EOL;
    echo "Caption: " . $media->getCaption() . PHP_EOL;
    echo "Timestamp: " . $media->getTimestamp() . PHP_EOL;
    echo "Date/Time: " . $media->getDatetime() . PHP_EOL;
    echo "Width: " . $media->getWidth() . PHP_EOL;
    echo "Height: " . $media->getHeight() . PHP_EOL;
    echo "Nb Likes: " . $media->getNbLikes() . PHP_EOL;
    echo "Nb Comments: " . $media->getNbComments() . PHP_EOL;
    echo "URL: " . $media->getUrl() . PHP_EOL;

    foreach ($media->getThumbnails() as $size => $thumbUrl) {
        echo "Thumbnail size " . $size . ": " . $thumbUrl . PHP_EOL;
    }
}

```
