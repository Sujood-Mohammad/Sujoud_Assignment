proc sortList {L} {
    set list $L
    set length [llength $list]

    for {set i 0} {$i < $length} {incr i} {
        for {set j 0} {$j < [expr {$length - $i - 1}]} {incr j} {
            if {[lindex $list $j] > [lindex $list [expr {$j + 1}]]} {
                set temp [lindex $list $j]
                lset list $j [lindex $list [expr {$j + 1}]]
                lset list [expr {$j + 1}] $temp
            }
        }
    }

    return $list
}

puts [sortList {3 6 8 7 0 1 4 2 9 5}] 
