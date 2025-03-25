const express = require("express");
const mysql = require("mysql");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "posyandu"
});

db.connect((err) => {
  if (err) {
    console.error("Database connection failed: " + err.stack);
    return;
  }
  console.log("Connected to database");
});

//endpoint GET semua data balita
app.get("/balita", (req, res) => {
  db.query("SELECT * FROM balita", (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(results);
  });
});

//endpoint GET detail balita berdasarkan ID
app.get("/detailbalita", (req, res) => {
  const id = req.query.id;
  if (!id) return res.status(400).json({ error: "ID balita diperlukan" });
  
  db.query("SELECT * FROM balita WHERE id = ?", [id], (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    if (results.length === 0) return res.status(404).json({ message: "Data tidak ditemukan" });
    res.status(200).json(results[0]);
  });
});

//endpoint POST untuk menambahkan data balita
app.post("/balita", (req, res) => {
  const { nama, nama_ortu, gender, no_hp, alamat, tempat_lahir, tanggal_lahir } = req.body;
  if (!nama || !nama_ortu || !gender || !no_hp || !alamat || !tempat_lahir || !tanggal_lahir) {
    return res.status(400).json({ error: "Semua field harus diisi" });
  }
  
  db.query("INSERT INTO balita (nama, nama_ortu, gender, no_hp, alamat, tempat_lahir, tanggal_lahir) VALUES (?, ?, ?, ?, ?, ?, ?)",
    [nama, nama_ortu, gender, no_hp, alamat, tempat_lahir, tanggal_lahir],
    (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      res.status(201).json({ message: "Data balita berhasil ditambahkan" });
    }
  );
});

//endpoint DELETE untuk menghapus balita berdasarkan ID
app.delete("/balita", (req, res) => {
  const id = req.query.id;
  if (!id) return res.status(400).json({ error: "ID balita diperlukan" });
  
  db.query("DELETE FROM balita WHERE id = ?", [id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ message: "Data balita berhasil dihapus" });
  });
});

const PORT = 5000;
app.listen(PORT, () => {
  console.log(`Server berjalan di http://localhost:${PORT}`);
});
