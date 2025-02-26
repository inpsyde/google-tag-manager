<?php

declare(strict_types=1);

namespace Inpsyde\GoogleTagManager\Renderer;

trait PrintInlineScriptTrait
{
    /**
     * Safely print inline scripts with fallback for older WordPress versions.
     *
     * @param string $script
     * @param array<string, string|bool> $attributes
     */
    protected function printInlineScript(string $script, array $attributes): void
    {
        if (function_exists('wp_print_inline_script_tag')) {
            wp_print_inline_script_tag($script, $attributes);

            return;
        }

        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        printf(
            '<script%1$s>%2$s</script>',
            $this->prepareAttributes($attributes),
            $script,
        );
    }

    /**
     * Fallback if WordPress < 5.7.0 and we have to manually print the script tag.
     *
     * @param array<string, string|bool> $attributes
     *
     * @return string
     */
    protected function prepareAttributes(array $attributes): string
    {
        $attributesString = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value) && $value) {
                $attributesString = sprintf(' %1$s="%2$s"', esc_attr($name), esc_attr($name));
                continue;
            }
            if (!is_bool($value)) {
                $attributesString = sprintf(' %1$s="%2$s"', esc_attr($name), esc_attr($value));
            }
        }

        return $attributesString;
    }
}
