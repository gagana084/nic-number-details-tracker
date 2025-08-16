<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
date_default_timezone_set("Asia/Colombo");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (empty($_GET['nic'])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => "NIC number is required",
        "error_code" => "MISSING_NIC"
    ]);
    exit;
}

$nic = strtoupper(trim($_GET['nic']));
$response = getNICDetails($nic);

http_response_code($response['success'] ? 200 : 400);
echo json_encode($response, JSON_PRETTY_PRINT);

function getNICDetails($nic)
{
    $len = strlen($nic);
    
    try {
        if ($len === 10 && preg_match('/^\d{9}[VX]$/', $nic)) {
            $year = "19" . substr($nic, 0, 2);
            $dayNo = intval(substr($nic, 2, 3));
            $serial = substr($nic, 5, 3);
            $checkDigit = substr($nic, 8, 1);
            $formatSuffix = substr($nic, 9, 1);
            $nicFormat = "Old Format (10-digit)";
            
            if (intval(substr($nic, 0, 2)) > 24) {
                return createErrorResponse("Invalid year in old NIC format", "INVALID_YEAR");
            }
            
        } elseif ($len === 12 && preg_match('/^\d{12}$/', $nic)) {
            $year = substr($nic, 0, 4);
            $dayNo = intval(substr($nic, 4, 3));
            $serial = substr($nic, 7, 3);
            $checkDigit = substr($nic, 10, 1);
            $formatSuffix = substr($nic, 11, 1);
            $nicFormat = "New Format (12-digit)";
            
            if (intval($year) < 1900 || intval($year) > date('Y')) {
                return createErrorResponse("Invalid birth year", "INVALID_YEAR");
            }
            
        } else {
            return createErrorResponse("Invalid NIC format. Use 10-digit (e.g., 991234567V) or 12-digit (e.g., 199912345678) format", "INVALID_FORMAT");
        }

        $originalDayNo = $dayNo;
        $isFemale = $dayNo > 500;
        if ($isFemale) $dayNo -= 500;
        
        if ($dayNo < 1 || $dayNo > 366) {
            return createErrorResponse("Invalid day number in NIC", "INVALID_DAY");
        }

        $birthYear = intval($year);
        $dob = DateTime::createFromFormat('Y-m-d', "$birthYear-01-01");
        
        if (!$dob) {
            return createErrorResponse("Unable to calculate birth date", "DATE_CALCULATION_ERROR");
        }
        
        $isLeapYear = ($birthYear % 4 == 0 && $birthYear % 100 != 0) || ($birthYear % 400 == 0);
        $maxDays = $isLeapYear ? 366 : 365;
        
        if ($dayNo > $maxDays) {
            return createErrorResponse("Invalid day number for the birth year", "INVALID_DAY_FOR_YEAR");
        }
        
        $dob->modify('+' . ($dayNo - 2) . ' days');
        $birthDate = $dob->format('Y-m-d');
        $birthDateFormatted = $dob->format('F j, Y');

        $today = new DateTime("now", new DateTimeZone("Asia/Colombo"));
        $ageInterval = $today->diff($dob);
        $ageInYears = $ageInterval->y;
        $ageInMonths = $ageInterval->m;
        $ageInDays = $ageInterval->d;
        $totalDays = $today->diff($dob)->days;

        $gender = $isFemale ? "Female" : "Male";
        $genderEmoji = $isFemale ? "👩" : "👨";

        $weekday = $dob->format('l');
        $weekdayShort = $dob->format('D');

        $generation = calculateGeneration($birthYear);

        $votingEligible = $ageInYears >= 18;
        $votingAge = $votingEligible ? "Eligible to vote" : "Not eligible to vote (under 18)";
        
        $votingEligibilityDate = null;
        if (!$votingEligible) {
            $eligibilityDate = clone $dob;
            $eligibilityDate->modify('+18 years');
            $votingEligibilityDate = $eligibilityDate->format('Y-m-d');
        }

        $governmentRetirement = $birthYear + 60;
        $privateRetirement = $birthYear + 55;

        $zodiacSign = getZodiacSign($dob->format('m'), $dob->format('d'));

        $nextBirthday = clone $dob;
        $nextBirthday->setDate($today->format('Y'), $dob->format('m'), $dob->format('d'));
        if ($nextBirthday <= $today) {
            $nextBirthday->modify('+1 year');
        }
        $daysUntilBirthday = $today->diff($nextBirthday)->days;

        $districtInfo = getDistrictInfo($serial);

        $lifeStage = calculateLifeStage($ageInYears);

        return [
            "success" => true,
            "nic_number" => $nic,
            "nic_format" => $nicFormat,
            "personal_information" => [
                "gender" => $gender,
                "gender_emoji" => $genderEmoji,
                "birth_date" => $birthDate,
                "birth_date_formatted" => $birthDateFormatted,
                "age_years" => $ageInYears,
                "age_months" => $ageInMonths,
                "age_days" => $ageInDays,
                "age_formatted" => "$ageInYears years, $ageInMonths months, $ageInDays days",
                "total_days_lived" => $totalDays,
                "day_of_year" => $dayNo,
                "life_stage" => $lifeStage
            ],
            "birth_details" => [
                "birth_day" => $weekday,
                "birth_day_short" => $weekdayShort,
                "zodiac_sign" => $zodiacSign,
                "is_leap_year" => $isLeapYear,
                "next_birthday" => $nextBirthday->format('Y-m-d'),
                "days_until_birthday" => $daysUntilBirthday
            ],
            "technical_information" => [
                "serial_number" => $serial,
                "check_digit" => $checkDigit,
                "format_suffix" => $formatSuffix,
                "original_day_number" => $originalDayNo,
                "district_code" => $districtInfo['code'],
                "district_name" => $districtInfo['name']
            ],
            "civic_information" => [
                "voting_eligibility" => $votingAge,
                "voting_eligible" => $votingEligible,
                "voting_eligibility_date" => $votingEligibilityDate,
                "generation" => $generation,
                "government_retirement_year" => $governmentRetirement,
                "private_retirement_year" => $privateRetirement
            ],
            "additional_insights" => [
                "birth_century" => getCentury($birthYear),
                "age_category" => getAgeCategory($ageInYears),
                "chinese_zodiac" => getChineseZodiac($birthYear),
                "birthstone" => getBirthstone($dob->format('n')),
                "season_born" => getSeason($dob->format('n'), $dob->format('j'))
            ],
            "generated_at" => date('Y-m-d H:i:s T'),
            "timezone" => "Asia/Colombo"
        ];
        
    } catch (Exception $e) {
        return createErrorResponse("An error occurred while processing the NIC: " . $e->getMessage(), "PROCESSING_ERROR");
    }
}

function createErrorResponse($message, $errorCode)
{
    return [
        "success" => false,
        "error" => $message,
        "error_code" => $errorCode,
        "generated_at" => date('Y-m-d H:i:s T')
    ];
}

function calculateGeneration($birthYear)
{
    if ($birthYear >= 2010) return "Generation Alpha";
    if ($birthYear >= 1997) return "Generation Z";
    if ($birthYear >= 1981) return "Millennial";
    if ($birthYear >= 1965) return "Generation X";
    if ($birthYear >= 1946) return "Baby Boomer";
    if ($birthYear >= 1928) return "Silent Generation";
    return "Greatest Generation";
}

function getZodiacSign($month, $day)
{
    $month = intval($month);
    $day = intval($day);
    
    $signs = [
        'Capricorn', 'Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini',
        'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius'
    ];
    
    $dates = [20, 19, 20, 20, 21, 21, 22, 23, 23, 23, 22, 22];
    
    return $signs[($month - 1) + ($day > $dates[$month - 1] ? 1 : 0) % 12];
}

function getChineseZodiac($year)
{
    $animals = [
        'Rat', 'Ox', 'Tiger', 'Rabbit', 'Dragon', 'Snake',
        'Horse', 'Goat', 'Monkey', 'Rooster', 'Dog', 'Pig'
    ];
    return $animals[($year - 1900) % 12];
}

function getBirthstone($month)
{
    $stones = [
        1 => 'Garnet', 2 => 'Amethyst', 3 => 'Aquamarine', 4 => 'Diamond',
        5 => 'Emerald', 6 => 'Pearl', 7 => 'Ruby', 8 => 'Peridot',
        9 => 'Sapphire', 10 => 'Opal', 11 => 'Topaz', 12 => 'Turquoise'
    ];
    return $stones[$month] ?? 'Unknown';
}

function getSeason($month, $day)
{
    if ($month >= 5 && $month <= 9) return "Southwest Monsoon";
    if ($month >= 10 && $month <= 1) return "Northeast Monsoon";
    return "Inter-monsoon";
}

function getCentury($year)
{
    return floor(($year - 1) / 100) + 1 . getOrdinalSuffix(floor(($year - 1) / 100) + 1) . " Century";
}

function getOrdinalSuffix($number)
{
    $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) return 'th';
    return $ends[$number % 10];
}

function getAgeCategory($age)
{
    if ($age < 13) return "Child";
    if ($age < 18) return "Teenager";
    if ($age < 30) return "Young Adult";
    if ($age < 50) return "Adult";
    if ($age < 65) return "Middle-aged Adult";
    return "Senior";
}

function calculateLifeStage($age)
{
    if ($age < 2) return "Infant";
    if ($age < 13) return "Child";
    if ($age < 18) return "Adolescent";
    if ($age < 25) return "Young Adult";
    if ($age < 40) return "Adult";
    if ($age < 60) return "Middle Age";
    return "Senior";
}

function getDistrictInfo($serial)
{
    $districts = [
        '001-100' => ['code' => '01', 'name' => 'Colombo'],
        '101-200' => ['code' => '02', 'name' => 'Gampaha'],
        '201-300' => ['code' => '03', 'name' => 'Kalutara'],
    ];
    
    $serialNum = intval($serial);
    foreach ($districts as $range => $info) {
        list($min, $max) = explode('-', $range);
        if ($serialNum >= intval($min) && $serialNum <= intval($max)) {
            return $info;
        }
    }
    
    return ['code' => 'Unknown', 'name' => 'Unknown District'];
}
?>