using Skutech.DataAccessLayer.Abstract;
using Skutech.DataAccessLayer.Concrete;
using Skutech.DataAccessLayer.Repositories;
using Skutech.EntityLayer;

namespace Skutech.DataAccessLayer.EntityFramework;

public class EfColorDal:GenericRepository<Color>,IColor
{
    public EfColorDal(Context context) : base(context)
    {
    }
}