import requests, time

BASE = "http://127.0.0.1:5000"

def test_post_get_and_delete_incident():
    payload = {"salle_id": 1, "description": "pytest functional test", "statut": "ouvert"}
    r = requests.post(f"{BASE}/incidents", json=payload)
    assert r.status_code == 201

    time.sleep(0.2)
    r2 = requests.get(f"{BASE}/incidents", params={"salle_id": 1})
    assert r2.status_code == 200
    data = r2.json()
    matches = [i for i in data if i.get("description") == "pytest functional test"]
    assert matches

    incident_id = matches[0]["id"]
    r3 = requests.delete(f"{BASE}/incidents/{incident_id}")
    assert r3.status_code == 200