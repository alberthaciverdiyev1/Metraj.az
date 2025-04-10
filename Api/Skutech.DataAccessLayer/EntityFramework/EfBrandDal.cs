using Skutech.DataAccessLayer.Abstract;
using Skutech.DataAccessLayer.Concrete;
using Skutech.DataAccessLayer.Repositories;
using Skutech.EntityLayer;

namespace Skutech.DataAccessLayer.EntityFramework;

public class EfBrandDal:GenericRepository<Brand>,IBrand
{
    public EfBrandDal(Context context) : base(context)
    {
    }
}