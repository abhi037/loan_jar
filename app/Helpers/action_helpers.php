<?php
if (!function_exists('renderActionDropdown')) {
    function renderActionDropdown(array $actions)
    {
        $user = auth()->user();
        $html = '';

        foreach ($actions as $action) {
            // Dynamically check the permission for each action
            if (!empty($action['permission']) && !$user->can($action['permission'])) {
                continue;  // Skip this action if the user doesn't have permission
            }

            // Build the action item HTML
            $html .= "<a class='dropdown-item' href='{$action['url']}' {$action['extra']}>
                        <i class='{$action['icon']}'></i> {$action['label']}
                      </a>";
        }

        if (empty($html)) {
            return '';  // No actions available based on permissions
        }

        return "<div class='dropdown d-inline-block user-dropdown'>
                    <button type='button' class='btn text-dark waves-effect' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <span class='d-xl-inline-block ms-1'>
                            <div class='badge bg-info-subtle text-info font-size-12'><i class='fa fa-cog'></i></div>
                        </span>
                        <i class='fa fa-angle-down'></i>
                    </button>
                    <div class='dropdown-menu dropdown-menu-end p-2'>{$html}</div>
                </div>";
    }
}

