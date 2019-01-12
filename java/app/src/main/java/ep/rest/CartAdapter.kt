package ep.rest

import android.app.Activity
import android.content.Context
import android.util.Log
import android.util.TypedValue
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ArrayAdapter
import android.widget.Button
import android.widget.TextView
import retrofit2.Call
import java.util.*

class CartAdapter(context: Context) : ArrayAdapter<CartItem>(context, 0, ArrayList()) {

    override fun getView(position: Int, convertView: View?, parent: ViewGroup): View {
        // Check if an existing view is being reused, otherwise inflate the view
        val view = if (convertView == null)
            LayoutInflater.from(context).inflate(R.layout.cartlist_element, parent, false)
        else
            convertView

        val tvNaziv = view.findViewById<TextView>(R.id.tvNaziv)
        val tvKolicina = view.findViewById<TextView>(R.id.tvKolicina)
        val tvPrice = view.findViewById<TextView>(R.id.tvPrice)
        val btnIzbrisi = view.findViewById<Button>(R.id.btnIzbrisi)

        val app = context.applicationContext as PivomatApp
        val cartItem = getItem(position)
        btnIzbrisi.setOnClickListener {

            CartService.instance.delete(cartItem.id, app.cookie!!).enqueue(object: retrofit2.Callback<Void> {
                override fun onResponse(call: Call<Void>?, response: retrofit2.Response<Void>?) {
                    if (response!!.isSuccessful){
                        koncnaCena(cartItem.cena * cartItem.kol)
                        remove(cartItem)
                        Log.i("KOSARICA", "Uspesno odstranjen artikel")
                    } else {
                        Log.i("KOSARICA", "Neuspesno odstranjen artikel")
                    }
                }

                override fun onFailure(call: Call<Void>?, t: Throwable?) {
                    Log.i("KOSARICA", "Neuspel klic za izbris artikla")
                }
            })
        }




        tvNaziv.text = cartItem?.naziv
        tvKolicina.text = "Količina: " + cartItem?.kol.toString()
        tvPrice.text = String.format(Locale.ENGLISH, "Cena artikla: %.2f€", cartItem?.cena)

        return view
    }

    fun koncnaCena(odstranjenaCena: Double) {

        val app = context.applicationContext as PivomatApp
        val vsota = app.skupnaCena - odstranjenaCena
        app.skupnaCena = vsota
        val act = context as Activity
        val tvSkupnaCena = act.findViewById<TextView>(R.id.tvSkupnaCena)
        tvSkupnaCena.setTextSize(TypedValue.COMPLEX_UNIT_SP, 18.0F)
        tvSkupnaCena.text = String.format(Locale.ENGLISH, "Skupna cena: %.2f€", vsota)
    }
}


