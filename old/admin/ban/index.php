<?php

use \App\Legacy\Authorization;

// Check authorization and bounce back intruders
auth()->allow([
    Authorization::ROLE_ADMIN,
    Authorization::ROLE_JUDGE
]);

return 'admin/ban';
