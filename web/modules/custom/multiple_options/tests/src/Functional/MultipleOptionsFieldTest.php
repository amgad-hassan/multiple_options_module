<?php

namespace Drupal\Tests\multiple_options\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the Multiple Options Field settings form.
 *
 * @group multiple_options
 */
class MultipleOptionsFieldTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['multiple_options'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Tests the field settings form.
   */
  public function testFieldSettingsForm() {
    $admin_user = $this->drupalCreateUser(['administer site configuration']);
    $this->drupalLogin($admin_user);

    $this->drupalGet('admin/config/multiple_options/settings');
    $this->assertSession()->statusCodeEquals(200);
  }
}