using Skutech.DataAccessLayer.Abstract;
using Skutech.DataAccessLayer.Concrete;
using Skutech.DataAccessLayer.Repositories;
using Skutech.EntityLayer;

namespace Skutech.DataAccessLayer.EntityFramework;

public class EfPriceDal:GenericRepository<Price>,IPrice
{
    public EfPriceDal(Context context) : base(context)
    {
    }
}