<?php

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow([1, 2]);

return 'admin/ban';
