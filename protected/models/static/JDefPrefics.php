<?php

/**
 * Модель списка префиксов
 *
 */
class JDefPrefics
{
        /**
         * Статичные данные списка
         * 
         * @return type
         */
        protected static function data()
        {
                return array(
                        'Tele2'         => array(900, 902, 904, 908, 950, 951, 952, 953),
                        'Билайн'        => array(901, 903, 905, 906, 909, 960, 961, 962, 963, 964, 965, 967, 968),
                        'МТС'           => array(910, 913, 915, 916, 917, 919, 985, 967, 980, 981, 982, 983, 984, 985, 987, 988, 989),
                        'МегаФон'       => array(495, 812, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 933, 934, 936, 937, 938, 939, 997),
                );
                
        }
        
        public static function getOperatorByPhone($phone)
        {
                foreach (self::data() as $key => $value) {
                        if(array_search(substr($phone, 0, 3), $value) !== false){
                                return $key;
                                break;
                        }
                }
        }
}
