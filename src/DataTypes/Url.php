<?php


namespace michalrokita\BlueMediaSDK\DataTypes;


use michalrokita\BlueMediaSDK\Exceptions\StringIsNotValidUrlException;

class Url
{
    /**
     * @var string
     */
    private $url;

    /**
     * Url constructor.
     * @param string $url
     * @throws StringIsNotValidUrlException
     */
    public function __construct(string $url)
    {
        self::validate($url);
        $this->url = $url;
    }
    /**
     * @param string $url
     * @return static
     * @throws StringIsNotValidUrlException
     */
    public static function fromString(string $url): self
    {
        return new Url($url);
    }

    /**
     * @param string $url
     * @throws StringIsNotValidUrlException
     */
    private static function validate(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new StringIsNotValidUrlException();
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->url;
    }
}