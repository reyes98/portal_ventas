from flask import Flask,session, g, render_template,redirect, url_for, request,session ,escape, redirect,jsonify


from SqlCons import * 
import os


##
app= Flask(__name__)
app.secret_key = os.urandom(24)

@app.route('/',methods=['GET', 'POST'])
def home():
    if request.method == 'GET':
        if session ==None: 
            session.pop('user', None)
        else:
            return render_template("login.html")
    if request.method =="POST":
        consulta=Consultas()
 
        if consulta.login(request.form['user'],request.form['contra']):
            RunSession(request.form['user'],request.form['contra'])
            return redirect(url_for('tablaM'))
        else:
            print('nonas')
            return render_template("login.html")

@app.route('/Editproduct',methods=['GET', 'POST'])
def EditarProductos():

    if request.method =="POST":
        if request.values.get("editarP")!=None:
            #si se unde el boton editar ...
            pass
    return render_template("EditarP.html",infoP=['datas1','data2'])

@app.route('/Verproduct',methods=['GET', 'POST'])
def VerProductos():
    consulta=Consultas()
    consulta.detallePro('nombre')
    NombreP='Producto # 1'
    detalles=['Nombre1','ip_producto2','descripcion3','cod_barras4','catoria5']
    
    return render_template("VerP.html",NombreP=NombreP,detalles=detalles)

@app.route('/product',methods=['GET', 'POST'])
def productos():
    if request.method =="POST":
        if request.values.get("editar")!=None:
            #EDITAR UN PRODUCTO PUNTUAL
            idp=request.values.get("editar")

            return redirect(url_for('EditarProductos'))
        if request.values.get("ver")!=None:  
            #VER LOS PRODUCTOS 
            idp=request.values.get("ver")
            return redirect(url_for('VerProductos'))


        return render_template("productos.html")
    cabecera=['','','']
    columnas=[(1),(2),(3)]
    return render_template("productos.html")

@app.route('/adminUser',methods=['GET', 'POST'])
def admin():
    if request.method =="POST":
         return render_template("productos.html")
    return render_template("admin.html")
@app.route('/Registrar',methods=['GET', 'POST'])
def addUsu():
    if request.method =="POST":
         return render_template("registro.html")
    return render_template("registro.html")
#--------------------------------------
@app.route("/ses-Cls-hhm", methods=['GET','POST'])
def dropsession():
    print('cerrar')
    session.pop('user', None)
    return redirect(url_for('home'))
    
def RunSession(user,passw):
    print('inicia session')
    session['user'] = user  
    session['password']=passw 
    
    
if __name__=="__main__":
    app.run('0.0.0.0', 5000, debug=True)