<?php
namespace Orange;

use Orange\Database;
use Orange\SiteSettings;
use Orange\Utilities;
use Orange\OrangeException;

/**
 * The core Orange class.
 *
 * @since Orange 1.0
 */
class Orange {
    private \Orange\Database $database;
    private \Orange\SiteSettings $settings;
    private string $version;
    public array $options;


    /**
     * Initialize core Orange classes.
     *
     * @since Orange 1.0
     */
    public function __construct($host, $user, $pass, $db) {
        $this->makeVersionString();

        session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

        $this->options = [];
        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
        }

        try {
            $this->database = new \Orange\Database($host, $user, $pass, $db);
            $this->settings = new \Orange\SiteSettings($this->database);
        } catch (OrangeException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database class for other Orange classes to use.
     *
     * @since Orange 1.0
     *
     * @return Database
     */
    public function getDatabase(): \Orange\Database {
        return $this->database;
    }

    /**
     * Returns the site settings class for other Orange classes to use.
     *
     * @since Orange 1.1
     *
     * @return SiteSettings
     */
    public function getSettings(): \Orange\SiteSettings {
        return $this->settings;
    }

    /**
     * Make Orange's version number.
     *
     * @since Orange 1.0
     */
    private function makeVersionString()
    {
        // Versioning guide (By Bluffingo, last updated 12/19/2023):
        //
        // * Bump the first number (X.xx) only if a major internal codebase update occurs.
        // * Bump the second number (x.XX) only if it's a feature update, say for Qobo.
        // * We do not have a third number unlike Semantic Versioning or something like Minecraft, since
        // we use Git hashes for indicating revisions, but this may change.
        $version = "1.1";
        $gitPath = __DIR__ . '/../../.git';

        // Check if the instance is git cloned. If it is, have the version string be
        // precise. Otherwise, just indicate that it's a "Non-source copy", though we
        // should find a better term for this. -Bluffingo 12/19/2023
        if(file_exists($gitPath)) {
            $gitHead = file_get_contents($gitPath . '/HEAD');
            $gitBranch = rtrim(preg_replace("/(.*?\/){2}/", '', $gitHead));
            $commit = file_get_contents($gitPath . '/refs/heads/' . $gitBranch); // kind of bad but hey it works

            $hash = substr($commit, 0, 7);

            $this->version = sprintf('%s.%s-%s', $version, $hash, $gitBranch);
        } else {
            $this->version = sprintf('%s (Non-source copy)', $version);
        }
    }

    /**
     * Returns Orange's version number. Originally named getBettyVersion().
     *
     * @since Orange 1.0
     */
    public function getVersionString(): string
    {
        return $this->version;
    }

    /**
     * Returns the user's local settings.
     *
     * @since Orange 1.0
     *
     * @return array
     */
    public function getLocalOptions(): array
    {
        return $this->options;
    }
}