import requests, time

BASE = "http://127.0.0.1:5000"

def test_full_flow_incident():
    payload = {"salle_id": 1, "description": "integration test", "statut": "En attente"}
    r = requests.post(f"{BASE}/incidents", json=payload)
    assert r.status_code == 201

    time.sleep(0.2)
    r2 = requests.get(f"{BASE}/incidents", params={"salle_id": 1})
    found = [i for i in r2.json() if i.get("description") == "integration test"]
    assert found

    incident_id = found[0]["id"]
    r3 = requests.put(f"{BASE}/incidents/{incident_id}", json={"description": "updated", "statut":"Résolu"})
    assert r3.status_code == 200

    r4 = requests.delete(f"{BASE}/incidents/{incident_id}")
    assert r4.status_code == 200