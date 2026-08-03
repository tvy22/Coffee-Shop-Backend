# Coffee Shop API Documentation

* **Base URL:** `http://127.0.0.1:8000/api`
* **Default Headers:**
  * `Accept: application/json`
  * `Content-Type: application/json`

---

## Database Schema Summary
* **users:** id, name, email, password, role ('client'|'staff')
* **categories:** id, name, image
* **drinks:** id, category_id, name, unit_price, in_stock (boolean), image
* **orders:** id, user_id (nullable), order_type ('takeaway'|'dine-in'), status ('pending'|'preparing'|'completed'), total, order_date
* **order_details:** id, order_id, drink_id, quantity, amount

## Endpoint Summary

