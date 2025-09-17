from datetime import datetime

def is_valid_date(date_str):
    try:
        datetime.strptime(date_str, "%Y-%m-%d")
        return True
    except ValueError:
        return False

def test_valid_date():
    assert is_valid_date("2025-09-11") is True

def test_invalid_date():
    assert is_valid_date("2025-13-01") is False
