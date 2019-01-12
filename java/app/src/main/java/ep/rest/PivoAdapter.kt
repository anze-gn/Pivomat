package ep.rest

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ArrayAdapter
import android.widget.TextView
import java.util.*

class PivoAdapter(context: Context) : ArrayAdapter<Pivo>(context, 0, ArrayList()) {

    override fun getView(position: Int, convertView: View?, parent: ViewGroup): View {
        // Check if an existing view is being reused, otherwise inflate the view
        val view = if (convertView == null)
            LayoutInflater.from(context).inflate(R.layout.pivolist_element, parent, false)
        else
            convertView

        val tvTitle = view.findViewById<TextView>(R.id.tvTitle)
        val tvAuthor = view.findViewById<TextView>(R.id.tvAuthor)
        val tvPrice = view.findViewById<TextView>(R.id.tvPrice)

        val pivo = getItem(position)

        tvTitle.text = pivo?.naziv
        tvAuthor.text = pivo?.imeZnamke
        tvPrice.text = String.format(Locale.ENGLISH, "%.2f EUR", pivo?.cena)

        return view
    }
}
