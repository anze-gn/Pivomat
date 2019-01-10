package ep.rest

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ArrayAdapter
import android.widget.Button
import android.widget.TextView
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

        val cartItem = getItem(position)

        tvNaziv.text = cartItem?.naziv
        tvKolicina.text = cartItem?.kolicina.toString()
        tvPrice.text = String.format(Locale.ENGLISH, "%.2f EUR", cartItem?.cena)

        return view
    }
}