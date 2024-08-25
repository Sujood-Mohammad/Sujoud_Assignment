proc reverse {str} {
    set words [split $str " "]
    
    set reversedWords [lreverse $words]

    return [join $reversedWords " "]
}

# اختبار الإجراء
puts [reverse "TCL is a Tool Command Language"]
puts [reverse "Welcome to you"]
