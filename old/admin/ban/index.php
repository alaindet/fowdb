<?php

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow([1, 3]);

return 'admin/ban';
