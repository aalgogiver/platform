<?php

namespace Oro\Bundle\LayoutBundle\Theme;

use Oro\Bundle\LayoutBundle\Model\Theme;

class ThemeManager
{
    /** @var ThemeFactoryInterface */
    protected $themeFactory;

    /** @var array */
    protected $themeDefinitions;

    /** @var string */
    protected $activeTheme;

    /** @var Theme[] */
    protected $instances = [];

    /**
     * @param ThemeFactoryInterface $themeFactory
     * @param array                 $themeDefinitions
     * @param string|null           $activeTheme
     */
    public function __construct(ThemeFactoryInterface $themeFactory, array $themeDefinitions, $activeTheme = null)
    {
        $this->themeDefinitions = $themeDefinitions;
        $this->activeTheme      = $activeTheme;
        $this->themeFactory     = $themeFactory;
    }

    /**
     * @return string
     */
    public function getActiveTheme()
    {
        return $this->activeTheme;
    }

    /**
     * @param string $activeTheme Theme name
     */
    public function setActiveTheme($activeTheme)
    {
        $this->activeTheme = $activeTheme;
    }

    /**
     * Returns all known themes names
     *
     * @return string[]|array
     */
    public function getThemeNames()
    {
        return array_keys($this->themeDefinitions);
    }

    /**
     * Check whether given theme is known by manager
     *
     * @param string $themeName
     *
     * @return bool
     */
    public function hasTheme($themeName)
    {
        return isset($this->themeDefinitions[$themeName]);
    }

    /**
     * Gets theme model instance
     *
     * @param string $themeName
     *
     * @return Theme
     */
    public function getTheme($themeName)
    {
        if (!$this->hasTheme($themeName)) {
            throw new \LogicException(sprintf('Unable to retrieve definition for theme "%s"', $themeName));
        }

        if (!isset($this->instances[$themeName])) {
            $this->instances[$themeName] = $this->themeFactory->create($themeName, $this->themeDefinitions[$themeName]);
        }

        return $this->instances[$themeName];
    }

    /**
     * @return Theme[]
     */
    public function getAllThemes()
    {
        $names = $this->getThemeNames();

        return array_combine(
            $names,
            array_map(
                function ($themeName) {
                    return $this->getTheme($themeName);
                },
                $names
            )
        );
    }
}
