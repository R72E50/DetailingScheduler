<?php

class Calendar {

    private $active_year, $active_month, $active_day;
    
    private $events = [];

    public function __construct($date = null) {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function __toString() {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '
                <div class="day_num ignore">
                    ' . ($num_days_last_month-$i+1) . '
                </div>
            ';
        }
        $isReserved = false; // Initialize a variable to track "Reserved" status

        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            $currentDate = date('Y-m-d');
            $SelectedDate = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i));

            if ($SelectedDate == $currentDate) {
                $selected = ' selected';
            }

            if ($currentDate > date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i))) {
                $html .= '<div class="day_num unavailable' . $selected . '">';
                $html .= '<span>' . $i . '</span>';
                $html .= '</div>';
            } else {
                $html .= '<div class="day_num ' . $selected . '">';

                $isReserved = false; // Initialize the variable for each day

                
                // Check if there's an event for this day with a status of 'Reserved'
                foreach ($this->events as $event) {
                    if ($event[0] === 'Reserved' && $SelectedDate == date('Y-m-d', strtotime($event[1]))) {
                        $isReserved = true;
                        break; // Exit the loop if a reserved event is found
                    }
                }


                 // Check if any date within the duration is reserved
                if (!$isReserved) {
                    if (isset($event[2]) && is_numeric($event[2])) {
                        for ($d = 0; $d <= ($event[2] - 1); $d++) {
                            $checkDate = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day'));

                            // If any date within the duration is reserved, mark it as Reserved
                            foreach ($this->events as $event) {
                                if ($event[0] === 'Reserved' && $checkDate == date('Y-m-d', strtotime($event[1]))) {
                                    $isReserved = true;
                                    $status = 'Reserved';
                                    break 2; // Exit both loops
                                }
                            }
                        }
                    }
                }

                if ($isReserved) {
                    $status = 'Reserved';
                    $html .= '<a data-status="' . $status . '">';
                } else {
                    
                    $service = $_GET['services'];
                    $status = 'Available';
                    $html .= '<a href="../webpages/book.php?date=' . date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i)) . '&services=' . $service . '" data-status="' . $status . '">';
                }
                $html .= '<span>' . $i . '</span>';

                $eventFound = false;
                foreach ($this->events as $event) {
                    for ($d = 0; $d <= ($event[2] - 1); $d++) {
                        $checkDate = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i . ' -' . $d . ' day'));

                        // Check if the status is 'Reserved' for the given date
                        if ($event[0] === 'Reserved' && $checkDate == date('Y-m-d', strtotime($event[1]))) {
                            $html .= '<div class="event' . $event[3] . '">';
                            $html .= $event[0];
                            $html .= '</div>';
                            $eventFound = true;
                            break; // Exit the inner loop if a confirmed event is found
                        }
                    }
                }

                if (!$eventFound) {
                    $html .= '<div class="d-none">Available</div>';
                }
                $html .= '</a>';
                $html .= '</div>';

                
            }
            
                
        }
        for ($i = 1; $i <= (42-$num_days-max($first_day_of_week, 0)); $i++) {
            $html .= '
                <div class="day_num ignore">
                    ' . $i . '
                </div>
            ';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}
?>
