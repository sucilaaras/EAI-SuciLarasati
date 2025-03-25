from flask import Flask, jsonify, request
from flask_mysqldb import MySQL

app = Flask(__name__)

app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'posyandu'

mysql = MySQL(app)

@app.route('/')
def utama():
    return jsonify({"message": "Selamat datang di Portal Data Balita Kabupaten Semarang"}), 200

#endpoint GET semua data balita
@app.route('/balita', methods=['GET'])
def get_balita():
    try:
        cursor = mysql.connection.cursor()
        cursor.execute("SELECT * FROM balita")
        
        column_names = [i[0] for i in cursor.description]
        balita_list = [dict(zip(column_names, row)) for row in cursor.fetchall()]
        
        cursor.close()
        return jsonify(balita_list), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500 

#endpoint GET detail balita berdasarkan ID
@app.route('/detailbalita', methods=['GET'])
def get_detail_balita():
    try:
        id_balita = request.args.get('id')
        if not id_balita:
            return jsonify({"error": "ID balita diperlukan"}), 400 
        
        cursor = mysql.connection.cursor()
        sql = "SELECT * FROM balita WHERE id = %s"
        cursor.execute(sql, (id_balita,))
        
        column_names = [i[0] for i in cursor.description]
        balita_data = [dict(zip(column_names, row)) for row in cursor.fetchall()]
        
        cursor.close()
        
        if not balita_data:
            return jsonify({"message": "Data tidak ditemukan"}), 404 
        
        return jsonify(balita_data), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500

#endpoint POST untuk menambahkan data balita
@app.route('/balita', methods=['POST'])
def add_balita():
    try:
        data = request.json 
        nama = data.get('nama')
        nama_ortu = data.get('nama_ortu')
        gender = data.get('gender')
        no_hp = data.get('no_hp')
        alamat = data.get('alamat')
        tempat_lahir = data.get('tempat_lahir')
        tanggal_lahir = data.get('tanggal_lahir')

        if not all([nama, nama_ortu, gender, no_hp, alamat, tempat_lahir, tanggal_lahir]):
            return jsonify({"error": "Semua field harus diisi"}), 400
        
        cursor = mysql.connection.cursor()
        sql = "INSERT INTO balita (nama, nama_ortu, gender, no_hp, alamat, tempat_lahir, tanggal_lahir) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        cursor.execute(sql, (nama, nama_ortu, gender, no_hp, alamat, tempat_lahir, tanggal_lahir))
        mysql.connection.commit()
        cursor.close()
        
        return jsonify({"message": "Data balita berhasil ditambahkan"}), 201
    except Exception as e:
        return jsonify({"error": str(e)}), 500

#endpoint DELETE untuk menghapus balita berdasarkan ID
@app.route('/balita', methods=['DELETE'])
def delete_balita():
    try:
        id_balita = request.args.get('id')
        if not id_balita:
            return jsonify({"error": "ID balita diperlukan"}), 400

        cursor = mysql.connection.cursor()
        sql = "DELETE FROM balita WHERE id = %s"
        cursor.execute(sql, (id_balita,))
        mysql.connection.commit()
        cursor.close()

        return jsonify({"message": "Data balita berhasil dihapus"}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
