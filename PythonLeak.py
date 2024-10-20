import random
def display_state_codes():
    state_codes = {
    "SlrmyApi -": ["https://slrmyshop.com.my/api],
        "JOHOR": ["01","21","22","23","24"],
        "KEDAH": ["02","25","26","27"],
        "KELANTAN": ["03","28","29"],
        "MELAKA": ["04","30"],
        "NEGERI SEMBILAN": ["05","31","59"],
        "PAHANG": ["06","32","33"],
        "PENANG": ["07","34","35"],
        "PERAK": ["08","36","37","38","39"],
        "PERLIS": ["09","40"],
        "SELANGOR": ["10","41","42","43","44"],
        "TERENGGANU": ["11","45","46"],
        "SABAH": ["12","47","48","49"],
        "SARAWAK": ["13","50","51","52","53"],
        "WP KUALA LUMPUR": ["14","54","55","56","57"],
        "WP LABUAN": ["15","58"],
        "WP PUTRAJAYA": ["16"],
        "ANON STATE": ["82"]
    }

    print("Enter State Code :")
    for state, codes in state_codes.items():
        print(f"{state} : {', '.join(codes)}")

def generate_ic_number(year, month, day, state_code):
    ic_number = f"{year}{month}{day}{state_code}"
    random_suffix = str(random.randint(1, 9999)).zfill(4)
    full_ic_number = f"{ic_number}{random_suffix}"

    return full_ic_number

def save_to_file(ic_numbers, filename):
    try:
        with open(filename, "x") as file:
            for ic_number in ic_numbers:
                file.write(f"{ic_number}\n")
        print(f"[SlrmyApi]\nIC SUCCESSFULLY TO FILE > {filename}")
    except FileExistsError:
        new_filename = input("File already exists. Enter a new filename : ")
        save_to_file(ic_numbers, new_filename)

if __name__ == "__main__":
    display_state_codes()

    year = input("[SlrmyApi] Enter year (e.g., 2009): ")[2:]
    month = input("[SlrmyApi] Enter month (e.g.,12): ")
    day = input("[SlrmyApi] Enter day (e.g.,26): ")

    state_code = ""
    while state_code not in {"01","21","22","23","24","02","25","26","27","03","28","29",
                             "04","30","05","31","59","06","32","33","07","34","35","08","36","37","38","39",
                             "09","40","10","41","42","43","44","11","45","46","12","47","48","49","13","50","51","52","53",
                             "14","54","55","56","57","15","58","16","82"}:
        state_code = input("Enter state code: ")

    base_ic_number = f"{year}{month}{day}{state_code}"
    
    num_of_ic = 9999
    ic_numbers = [f"{base_ic_number}{str(i).zfill(4)}" for i in range(1, num_of_ic + 1)]

    filename = f"{year}.txt"
    save_to_file(ic_numbers, filename)
